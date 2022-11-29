<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/config',
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/packages',
        __DIR__ . '/ecs.php',
        __DIR__ . '/rector.php',
    ]);

    $ecsConfig->sets([SetList::PSR_12, SetList::SYMPLIFY, SetList::COMMON, SetList::CLEAN_CODE]);

    $ecsConfig->skip([
        __DIR__ . '/config/bundles.php',

        DeclareStrictTypesFixer::class => [
            // smaller content
            __DIR__ . '/packages/Demo/data/demo-config.php',
            __DIR__ . '/packages/Demo/data/DemoFile.php',
        ],

        ClassAttributesSeparationFixer::class => [
            __DIR__ . '/packages/Demo/src/Controller/DemoController.php',
            // broken on attributes
            __DIR__ . '/packages/Demo/src/Entity/RectorRun.php',
        ],
    ]);
};
