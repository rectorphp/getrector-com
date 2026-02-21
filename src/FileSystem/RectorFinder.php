<?php

declare(strict_types=1);

namespace App\FileSystem;

use App\Exception\InvalidRuleDescriptionException;
use App\RuleFilter\ValueObject\RectorSet;
use App\RuleFilter\ValueObject\RuleMetadata;
use App\Sets\RectorSetsTreeProvider;
use Nette\Loaders\RobotLoader;
use Rector\Configuration\Deprecation\Contract\DeprecatedInterface;
use Rector\Contract\Rector\RectorInterface;
use Rector\PostRector\Contract\Rector\PostRectorInterface;
use ReflectionClass;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Throwable;
use Webmozart\Assert\Assert;

final readonly class RectorFinder
{
    /**
     * @var string[]
     */
    private const array CORE_DIRECTORIES = [
        __DIR__ . '/../../vendor/rector/rector/rules',
        __DIR__ . '/../../vendor/rector/rector/vendor/rector/rector-symfony/rules',
        __DIR__ . '/../../vendor/rector/rector/vendor/rector/rector-phpunit/rules',
        __DIR__ . '/../../vendor/rector/rector/vendor/rector/rector-doctrine/rules',
    ];

    public function __construct(
        private RectorSetsTreeProvider $rectorSetsTreeProvider,
    ) {
    }

    /**
     * @api
     * @return RuleMetadata[]
     */
    public function find(): array
    {
        return [...$this->findCore(), ...$this->findCommunity()];
    }

    /**
     * @api to be used
     * @return RuleMetadata[]
     */
    public function findCommunity(): array
    {
        // @see https://github.com/driftingly/rector-laravel
        // clone... parse... dump json serialized?
        $communityDirectory = __DIR__ . '/../../vendor/driftingly/rector-laravel/src';

        $rectorSets = array_merge(
            $this->rectorSetsTreeProvider->provide(),
            $this->rectorSetsTreeProvider->provideCommunity()
        );

        return $this->findInDirectoriesAndCreateRuleMetadatas([$communityDirectory], $rectorSets);
    }

    /**
     * @return RuleMetadata[]
     */
    public function findCore(): array
    {
        $rectorSets = $this->rectorSetsTreeProvider->provide();
        return $this->findInDirectoriesAndCreateRuleMetadatas(self::CORE_DIRECTORIES, $rectorSets);
    }

    public function findBySlug(string $slug): ?RuleMetadata
    {
        foreach ($this->findCore() as $ruleMetadata) {
            if ($ruleMetadata->getSlug() === $slug) {
                return $ruleMetadata;
            }
        }

        foreach ($this->findCommunity() as $ruleMetadata) {
            if ($ruleMetadata->getSlug() === $slug) {
                return $ruleMetadata;
            }
        }

        return null;
    }

    public function getRuleCount(): int
    {
        return count($this->findCore()) + count($this->findCommunity());
    }

    /**
     * @param string[] $directories
     * @return array<class-string<RectorInterface>>
     */
    private function findRectorClasses(array $directories): array
    {
        Assert::allDirectory($directories);

        $robotLoader = new RobotLoader();

        // note: skip downgrade on purpose, as not likely to be used explicitly but as part of set
        $robotLoader->addDirectory(...$directories);

        $robotLoader->acceptFiles = ['*Rector.php'];
        $robotLoader->setTempDirectory(\sys_get_temp_dir() . '/dump-rector');
        $robotLoader->rebuild();

        return array_keys($robotLoader->getIndexedClasses());
    }

    /**
     * @param RectorSet[] $rectorSets
     * @return RectorSet[]
     */
    private function findRuleUsedSets(RuleDefinition $ruleDefinition, array $rectorSets): array
    {
        $activeSets = [];
        foreach ($rectorSets as $rectorSet) {
            if ($rectorSet->hasRule($ruleDefinition->getRuleClass())) {
                $activeSets[] = $rectorSet;
            }
        }

        return $activeSets;
    }

    /**
     * @param string[] $directories
     * @param RectorSet[] $rectorSets
     * @return RuleMetadata[]
     */
    private function findInDirectoriesAndCreateRuleMetadatas(array $directories, array $rectorSets): array
    {
        Assert::allDirectory($directories);
        Assert::allIsAOf($rectorSets, RectorSet::class);

        $ruleMetadatas = [];

        $findRectorClasses = $this->findRectorClasses($directories);

        foreach ($findRectorClasses as $findRectorClass) {
            $rectorReflectionClass = new ReflectionClass($findRectorClass);
            if ($rectorReflectionClass->isAbstract()) {
                continue;
            }

            // skip @deprecated ones
            if (str_contains((string) $rectorReflectionClass->getDocComment(), '@deprecated')) {
                continue;
            }

            if ($rectorReflectionClass->isSubclassOf(DeprecatedInterface::class)) {
                continue;
            }

            // no definition
            if ($rectorReflectionClass->isSubclassOf(PostRectorInterface::class)) {
                continue;
            }

            try {
                $rector = $rectorReflectionClass->newInstanceWithoutConstructor();

                Assert::methodExists($rector, 'getRuleDefinition');
                /** @var RectorInterface $rector */
                $ruleDefinition = $rector->getRuleDefinition();

            } catch (Throwable $throwable) {
                throw new InvalidRuleDescriptionException(sprintf(
                    'Rule "%s" has invalid code samples:%s"%s"',
                    $findRectorClass,
                    PHP_EOL . PHP_EOL,
                    $throwable->getMessage()
                ), $throwable->getCode(), $throwable);
            }

            $ruleDefinition->setRuleClass($findRectorClass);

            $currentRuleSets = $this->findRuleUsedSets($ruleDefinition, $rectorSets);

            $ruleMetadatas[] = new RuleMetadata(
                $ruleDefinition->getRuleClass(),
                $ruleDefinition->getDescription(),
                $ruleDefinition->getCodeSamples(),
                $currentRuleSets,
                (string) $rectorReflectionClass->getFileName(),
            );
        }

        return $ruleMetadatas;
    }
}
