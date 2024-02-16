<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/config', __DIR__ . '/src', __DIR__ . '/tests', __DIR__ . '/routes'])
    ->withImportNames(removeUnusedImports: true)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        naming: true,
        privatization: true,
        typeDeclarations: true
    )
    ->withRules([\Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector::class])
    ->withSkip([
        // generated
        __DIR__ . '/src/PhpParser/ClickablePrinter.php',
        '*/Fixture/*',
        '*/Expected/*',

        // fix date time on master
        \Rector\Naming\Rector\Class_\RenamePropertyToMatchTypeRector::class,

        // keep FQN names to avoid scoping
        \Rector\Php55\Rector\String_\StringClassNameToClassConstantRector::class => [__DIR__ . '/utils/Rector'],
    ]);
