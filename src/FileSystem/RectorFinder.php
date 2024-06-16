<?php

declare(strict_types=1);

namespace Rector\Website\FileSystem;

use Nette\Loaders\RobotLoader;
use Rector\Contract\Rector\RectorInterface;
use Rector\PostRector\Contract\Rector\PostRectorInterface;
use Rector\Website\Enum\RuleNodeRedirectMap;
use Rector\Website\RuleFilter\ValueObject\RectorSet;
use Rector\Website\RuleFilter\ValueObject\RuleMetadata;
use Rector\Website\Sets\RectorSetsTreeProvider;
use ReflectionClass;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RectorFinder
{
    public function __construct(
        private readonly RectorSetsTreeProvider $rectorSetsTreeProvider,
    ) {
    }

    /**
     * @return RuleMetadata[]
     */
    public function findCore(): array
    {
        $rectorSets = $this->rectorSetsTreeProvider->provide();

        // 1. find all rector rules
        $ruleMetadatas = [];

        foreach ($this->findRectorClasses() as $rectorClass) {
            $rectorReflectionClass = new ReflectionClass($rectorClass);
            if ($rectorReflectionClass->isAbstract()) {
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
                $currentRuleSets
            );
        }

        // @todo this should be possibly cached to json, as heavy on load

        return $ruleMetadatas;
    }

    /**
     * @return array<class-string<RectorInterface>>
     */
    private function findRectorClasses(): array
    {
        $robotLoader = new RobotLoader();

        // note: skip downgrade on purpose, as not likely to be used explicitly but as part of set
        $robotLoader->addDirectory(__DIR__ . '/../../vendor/rector/rector/rules');
        $robotLoader->addDirectory(__DIR__ . '/../../vendor/rector/rector/vendor/rector/rector-symfony/rules');
        $robotLoader->addDirectory(__DIR__ . '/../../vendor/rector/rector/vendor/rector/rector-phpunit/rules');
        $robotLoader->addDirectory(__DIR__ . '/../../vendor/rector/rector/vendor/rector/rector-doctrine/rules');

        $robotLoader->acceptFiles = ['*Rector.php'];
        $robotLoader->setTempDirectory(\sys_get_temp_dir() . '/dump-rector');
        $robotLoader->rebuild();

        return array_keys($robotLoader->getIndexedClasses());
    }

    /**
     * @param RectorSet[] $rectorSets
     * @return string[]
     */
    private function findRuleUsedSets(RuleDefinition $ruleDefinition, array $rectorSets): array
    {
        $activeSets = [];
        foreach ($rectorSets as $rectorSet) {
            if ($rectorSet->hasRule($ruleDefinition->getRuleClass())) {
                $activeSets[] = $rectorSet->getName();
            }
        }

        return $activeSets;
    }
}
