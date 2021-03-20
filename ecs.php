<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\Operator\UnaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PATHS, [
        __DIR__ . '/config',
        __DIR__ . '/src',
        __DIR__ . '/packages',
        __DIR__ . '/ecs.php',
        __DIR__ . '/rector.php',
    ]);

    $parameters->set(Option::SETS, [SetList::PSR_12, SetList::SYMPLIFY, SetList::COMMON, SetList::CLEAN_CODE]);

    $parameters->set(Option::SKIP, [
        __DIR__ . '/config/bundles.php',

        // broken on PHP 8.0
        ClassAttributesSeparationFixer::class,
        UnaryOperatorSpacesFixer::class,

        DeclareStrictTypesFixer::class => [
            // smaller content
            __DIR__ . '/packages/demo/data/demo-config.php',
            __DIR__ . '/packages/demo/data/DemoFile.php',
        ],

        ClassAttributesSeparationFixer::class => [
            __DIR__ . '/packages/demo/src/Lint/ForbiddenPHPFunctionsChecker.php',
            __DIR__ . '/packages/demo/src/Controller/DemoController.php',
            // broken on attributes
            __DIR__ . '/packages/demo/src/Entity/RectorRun.php',
        ],
    ]);
};
