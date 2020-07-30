<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('doctrine', [
        'dbal' => [
            'driver' => 'pdo_mysql',
            'server_version' => '5.7',
            'host' => '%env(DATABASE_HOST)%',
            'port' => '%env(DATABASE_PORT)%',
            'dbname' => '%env(DATABASE_DBNAME)%',
            'user' => '%env(DATABASE_USER)%',
            'password' => '%env(DATABASE_PASSWORD)%',
            'charset' => '%env(DATABASE_CHARSET)%',
        ],
    ]);

    $containerConfigurator->extension('doctrine', [
        'orm' => [
            'auto_generate_proxy_classes' => true,
            'naming_strategy' => 'doctrine.orm.naming_strategy.underscore',
            'auto_mapping' => true,
        ],
    ]);
};
