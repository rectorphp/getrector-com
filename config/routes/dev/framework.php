<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension(
        '_errors',
        ['resource' => ref('FrameworkBundle/Resources/config/routing/errors.xml')]
    );

    $containerConfigurator->extension('_errors', ['prefix' => '/__error']);
};
