<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\Operator\UnaryOperatorSpacesFixer;
use SlevomatCodingStandard\Sniffs\Classes\UnusedPrivateElementsSniff;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\Configuration\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PATHS, [
        __DIR__ . '/src', __DIR__ . '/tests', __DIR__ . '/packages',
        __DIR__ . '/ecs.php',
    ]);

    $parameters->set(Option::SETS, [
        SetList::PSR_12, SetList::PHP_70, SetList::PHP_71, SetList::SYMPLIFY, SetList::COMMON, SetList::CLEAN_CODE,
    ]);

    $parameters->set(Option::SKIP, [
        UnaryOperatorSpacesFixer::class => null,
        UnusedPrivateElementsSniff::class . '.' . UnusedPrivateElementsSniff::CODE_WRITE_ONLY_PROPERTY => null,
        ClassAttributesSeparationFixer::class => [
            __DIR__ . '/packages/demo/src/Lint/ForbiddenPHPFunctionsChecker.php',
            __DIR__ . '/packages/demo/src/Controller/DemoController.php',
        ],
    ]
    );
};
