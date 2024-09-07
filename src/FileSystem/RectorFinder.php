<?php

declare(strict_types=1);

namespace App\FileSystem;

use App\RuleFilter\ValueObject\RectorSet;
use App\RuleFilter\ValueObject\RuleMetadata;
use App\Sets\RectorSetsTreeProvider;
use ArrayLookup\Finder;
use Nette\Loaders\RobotLoader;
use Rector\Contract\Rector\RectorInterface;
use Rector\PostRector\Contract\Rector\PostRectorInterface;
use ReflectionClass;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class RectorFinder
{
    /**
     * @var string[]
     */
    private const CORE_DIRECTORIES = [
        __DIR__ . '/../../vendor/rector/rector/rules',
        __DIR__ . '/../../vendor/rector/rector/vendor/rector/rector-symfony/rules',
        __DIR__ . '/../../vendor/rector/rector/vendor/rector/rector-phpunit/rules',
        __DIR__ . '/../../vendor/rector/rector/vendor/rector/rector-doctrine/rules',
    ];

    public function __construct(
        private readonly RectorSetsTreeProvider $rectorSetsTreeProvider,
    ) {
    }

    /**
     * @api will be used
     * @return RuleMetadata[]
     */
    public function findCommunity(): array
    {
        // @see https://github.com/driftingly/rector-laravel
        // clone... parse... dump json serialized?
        $comunityDirectory = __DIR__ . '/../../community-repository/laravel';

        $this->findRectorClasses([$comunityDirectory]);

        // dump($laravelRectorClasses);

        // find community ones
        // install rector-laravel locally
        // load it  :)
        return [];
    }

    /**
     * @return RuleMetadata[]
     */
    public function findCore(): array
    {
        $rectorSets = $this->rectorSetsTreeProvider->provide();

        // 1. find all rector rules
        $ruleMetadatas = [];

        foreach ($this->findRectorClasses(self::CORE_DIRECTORIES) as $rectorClass) {
            $rectorReflectionClass = new ReflectionClass($rectorClass);
            if ($rectorReflectionClass->isAbstract()) {
                continue;
            }

            // skip @deprecated ones
            if (str_contains((string) $rectorReflectionClass->getDocComment(), '@deprecated')) {
                continue;
            }

            // no definition
            if ($rectorReflectionClass->isSubclassOf(PostRectorInterface::class)) {
                continue;
            }

            $rector = $rectorReflectionClass->newInstanceWithoutConstructor();

            /** @var RectorInterface $rector */
            $ruleDefinition = $rector->getRuleDefinition();
            $ruleDefinition->setRuleClass($rectorClass);

            $currentRuleSets = $this->findRuleUsedSets($ruleDefinition, $rectorSets);

            $ruleMetadatas[] = new RuleMetadata(
                $ruleDefinition->getRuleClass(),
                $ruleDefinition->getDescription(),
                $ruleDefinition->getCodeSamples(),
                $currentRuleSets,
                (string) $rectorReflectionClass->getFileName()
            );
        }

        // @todo this should be possibly cached to json, as heavy on load

        return $ruleMetadatas;
    }

    public function findBySlug(string $slug): ?RuleMetadata
    {
        $filter = static fn (RuleMetadata $ruleMetadata): bool => $ruleMetadata->getSlug() === $slug;
        return Finder::first($this->findCore(), $filter);
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
        $filter = static fn (RectorSet $rectorSet): bool => $rectorSet->hasRule($ruleDefinition->getRuleClass());
        return Finder::rows($rectorSets, $filter);
    }
}
