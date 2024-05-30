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
        typeDeclarations: true,
        rectorPreset: true,
    )
    ->withRootFiles()
    ->withSkip([
        '*/Fixture/*',
        '*/Expected/*',
    ]);
