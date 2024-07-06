<?php

declare(strict_types=1);

namespace App\FileSystem;

use App\Enum\RuleNodeRedirectMap;
use App\RuleFilter\ValueObject\RectorSet;
use App\RuleFilter\ValueObject\RuleMetadata;
use App\Sets\RectorSetsTreeProvider;
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
    public function findCommunity(): array
    {
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

            if (isset(RuleNodeRedirectMap::MAP[$rectorClass])) {
                $changedNodeTypes = RuleNodeRedirectMap::MAP[$rectorClass];
            } else {
                $changedNodeTypes = $rector->getNodeTypes();
            }

            $ruleMetadatas[] = new RuleMetadata(
                $ruleDefinition->getRuleClass(),
                $ruleDefinition->getDescription(),
                $ruleDefinition->getCodeSamples(),
                $changedNodeTypes,
                $currentRuleSets,
                (string) $rectorReflectionClass->getFileName()
            );
        }

        // @todo this should be possibly cached to json, as heavy on load

        return $ruleMetadatas;
    }

    public function findBySlug(string $slug): ?RuleMetadata
    {
        foreach ($this->findCore() as $ruleMetadata) {
            if ($ruleMetadata->getSlug() === $slug) {
                return $ruleMetadata;
            }
        }

        return null;
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
}
