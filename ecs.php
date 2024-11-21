<?php

declare(strict_types=1);

use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
    ->withSkip([
        // regenerated
        __DIR__ . '/src/Ast/PhpParser/ClickablePrinter.php',
    ])
    ->withRootFiles()
    ->withRules([LineLengthFixer::class])
    ->withPreparedSets(psr12: true, common: true, symplify: true)
    ->withSkip(['*/Fixture/*', '*/Expected/*', __DIR__ . '/_ide_helper.php', __DIR__ . '/_ide_helper_models.php']);
