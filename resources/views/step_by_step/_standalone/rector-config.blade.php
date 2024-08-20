&lt;?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
    ->withRootFiles()

@if ($hasNamedArgs === false)
    @if ($step === 1)
        ->withPhp53Sets()
    @elseif ($step === 2)
        ->withPhp54Sets()
    @elseif ($step === 3)
        ->withPhp55Sets()
    @elseif ($step >= 4 && $step < \App\Enum\StepBreakpoint::PHP_70)
        ->withPhp56Sets()
    @elseif ($step == \App\Enum\StepBreakpoint::PHP_70)
        ->withPhp70Sets()
    @elseif ($step == \App\Enum\StepBreakpoint::PHP_70 + 1)
        ->withPhp71Sets()
    @elseif ($step == \App\Enum\StepBreakpoint::PHP_70 + 2)
        ->withPhp72Sets()
    @elseif ($step == \App\Enum\StepBreakpoint::PHP_70 + 3)
        ->withPhp73Sets()
    @elseif ($step >= \App\Enum\StepBreakpoint::PHP_70 + 4 && $step < \App\Enum\StepBreakpoint::PHP_80)
        ->withPhp74Sets()
    @elseif ($step == \App\Enum\StepBreakpoint::PHP_80)
        ->withPhp80Sets()
    @endif
@else
    @if ($step === 1)
        ->withPhpSets(php53: true)
    @elseif ($step === 2)
        ->withPhpSets(php54: true)
    @elseif ($step === 3)
        ->withPhpSets(php55: true)
    @elseif ($step >= 4 && $step < \App\Enum\StepBreakpoint::PHP_70)
        ->withPhpSets(php56: true)
    @elseif ($step == \App\Enum\StepBreakpoint::PHP_70 + 1)
        ->withPhpSets(php71: true)
    @elseif ($step == \App\Enum\StepBreakpoint::PHP_70 + 2)
        ->withPhpSets(php72: true)
    @elseif ($step == \App\Enum\StepBreakpoint::PHP_70 + 3)
        ->withPhpSets(php73: true)
    @elseif ($step >= \App\Enum\StepBreakpoint::PHP_70 + 4 && $step < \App\Enum\StepBreakpoint::PHP_80)
        ->withPhpSets(php74: true)
    @elseif ($step == \App\Enum\StepBreakpoint::PHP_80 + 1)
        ->withPhpSets(php80: true)
    @elseif ($step == \App\Enum\StepBreakpoint::PHP_80 + 2)
        ->withPhpSets(php81: true)
    @elseif ($step >= \App\Enum\StepBreakpoint::PHP_80 + 3)
        ->withPhpSets(php82: true)
    @endif
@endif

@if ($step >= 5)
    ->withPhpPolyfill()
@endif

@if ($step > \App\Enum\StepBreakpoint::TYPE_DECLARATION_LEVEL)
    ->withTypeDeclarationLevel({{ min($step - \App\Enum\StepBreakpoint::TYPE_DECLARATION_LEVEL, 50) }})
@endif

@if ($step > \App\Enum\StepBreakpoint::DEAD_CODE_LEVEL)
    ->withDeadCodeLevel({{ min($step - \App\Enum\StepBreakpoint::DEAD_CODE_LEVEL, 50) }})
@endif

@if ($step > \App\Enum\StepBreakpoint::CODE_QUALITY_LEVEL)
    ->withCodeQualityLevel({{ min($step - \App\Enum\StepBreakpoint::CODE_QUALITY_LEVEL, 50) }})
@endif

@if ($step >= \App\Enum\StepBreakpoint::PHP_80 + 4)
    ->withAttributes()
@endif

@if ($step == \App\Enum\StepBreakpoint::IMPORT_NAMES)
    ->withImportNames(importDocBlockNames: false)
@elseif ($step == \App\Enum\StepBreakpoint::IMPORT_NAMES + 1)
    ->withImportNames()
@elseif ($step >= \App\Enum\StepBreakpoint::IMPORT_NAMES + 2)
    ->withImportNames(removeUnusedImports: true)
@endif

@if ($step == \App\Enum\StepBreakpoint::PREPARED_SETS)
    ->withPreparedSets(codingStyle: true)
@elseif ($step == \App\Enum\StepBreakpoint::PREPARED_SETS + 1)
    ->withPreparedSets(codingStyle: true, privatization: true)
@elseif ($step >= \App\Enum\StepBreakpoint::PREPARED_SETS + 2)
    ->withPreparedSets(codingStyle: true, privatization: true, naming: true)
@endif

;
