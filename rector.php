<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Function_\CamelCaseFunctionNamingToUnderscoreRector;
use Rector\Core\Configuration\Option;
use Rector\DeadCode\Rector\Class_\RemoveUnusedDoctrineEntityMethodAndPropertyRector;
use Rector\Generic\Rector\FuncCall\FuncCallToStaticCallRector;
use Rector\Set\ValueObject\SetList;
use Rector\Transform\ValueObject\FuncCallToStaticCall;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\inline_service;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PATHS, [__DIR__ . '/src', __DIR__ . '/packages']);

    $parameters->set(Option::SETS, [
        SetList::DEAD_CODE, SetList::CODE_QUALITY, SetList::CODING_STYLE, SetList::TYPE_DECLARATION,
    ]);

    $parameters->set(Option::SKIP, [
        '*/var/cache/*',
        __DIR__ . '/packages/demo/data/DemoFile.php',

        // false positive removal
        RemoveUnusedDoctrineEntityMethodAndPropertyRector::class,

        // rename internal function to non-existing
        CamelCaseFunctionNamingToUnderscoreRector::class,
    ]);

    $services = $containerConfigurator->services();

    $services->set(FuncCallToStaticCallRector::class)
        ->call('configure', [[
            FuncCallToStaticCallRector::FUNC_CALLS_TO_STATIC_CALLS => [
                inline_service(FuncCallToStaticCall::class)
                    ->args(['dump', 'Tracy\Debugger', 'dump']),
            ],
        ]]);

    $services->set(FuncCallToStaticCallRector::class)
        ->call('configure', [[
            FuncCallToStaticCallRector::FUNC_CALLS_TO_STATIC_CALLS => ValueObjectInliner::inline([
                new FuncCallToStaticCall('dump', 'Tracy\Debugger', 'dump'),
            ]),
        ]]);
};
