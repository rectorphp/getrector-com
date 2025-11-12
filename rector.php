<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector;
use Rector\Config\RectorConfig;
use Rector\Naming\Rector\Foreach_\RenameForeachValueVariableToMatchMethodCallReturnTypeRector;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/config', __DIR__ . '/src', __DIR__ . '/tests', __DIR__ . '/routes'])
    ->withImportNames()
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        typeDeclarationDocblocks: true,
        privatization: true,
        naming: true,
        rectorPreset: true,
    )
    ->withPhpSets()
    ->withAttributesSets()
    ->withComposerBased(twig: true, doctrine: true, phpunit: true, symfony: true, netteUtils: true, laravel: true)
    ->withSkip([
        RenameForeachValueVariableToMatchMethodCallReturnTypeRector::class => [
            // metadata -> datum false positive
            __DIR__ . '/src/FileSystem/RectorFinder.php',
            __DIR__ . '/src/Controller/Socials/RuleThumbnailController.php',
        ],
        StringClassNameToClassConstantRector::class => [
            // non-existing class in /src
            __DIR__ . '/src/RuleFilter/ConfiguredDiffSamplesFactory.php',
        ],

        // not suitable for such a long content
        InlineArrayReturnAssignRector::class => [__DIR__ . '/src/Repository/RenovationItemRepository.php'],
    ])
    ->withRootFiles()
    ->withSkip([
        '*/Fixture/*',
        '*/Expected/*',
        // generated
        __DIR__ . '/src/Ast/PhpParser/ClickablePrinter.php',
    ]);
