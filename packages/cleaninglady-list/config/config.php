<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('Rector\\Website\\CleaningLadyList\\', __DIR__ . '/../src')
        ->exclude([__DIR__ . '/../src/Entity', __DIR__ . '/../src/Form']);
};
