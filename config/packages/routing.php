<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (RoutingConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('framework', [
        'router' => [
            'strict_requirements' => null,
        ],
    ]);
};
