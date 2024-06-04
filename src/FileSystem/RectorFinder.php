<?php

declare(strict_types=1);

namespace Rector\Website\FileSystem;

use Nette\Loaders\RobotLoader;
use Rector\Contract\Rector\RectorInterface;
use Rector\PostRector\Contract\Rector\PostRectorInterface;
use Rector\Rector\AbstractRector;
use ReflectionClass;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RectorFinder
{
    /**
     * @return RuleDefinition[]
     */
    public function findCore(): array
    {
        // 1. find all rector rules
        $ruleDefinitions = [];

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

            /** @var AbstractRector $rector */
            $ruleDefinition = $rector->getRuleDefinition();
            $ruleDefinition->setRuleClass($rectorClass);

            $ruleDefinitions[] = $ruleDefinition;
        }

        return $ruleDefinitions;
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
}
