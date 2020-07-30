<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension(
        'web_profiler_wdt',
        ['resource' => ref('WebProfilerBundle/Resources/config/routing/wdt.xml')]
    );

    $containerConfigurator->extension('web_profiler_wdt', ['prefix' => '/_wdt']);

    $containerConfigurator->extension(
        'web_profiler_profiler',
        ['resource' => ref('WebProfilerBundle/Resources/config/routing/profiler.xml')]
    );

    $containerConfigurator->extension('web_profiler_profiler', ['prefix' => '/_profiler']);
};
