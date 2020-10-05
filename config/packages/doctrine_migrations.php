<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('doctrine_migrations', [
        'dir_name' => '%kernel.project_dir%/migrations',
    ]);

    $containerConfigurator->extension('doctrine_migrations', [
        'namespace' => 'DoctrineMigrations',
    ]);
};
