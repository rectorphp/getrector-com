<?php

declare(strict_types=1);

use Rector\Website\ValueObject\Symfony\DoctrineExtension;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension(DoctrineExtension::NAME, [
        DoctrineExtension::DBAL => [
            'driver' => 'pdo_mysql',
            'server_version' => '5.7',
            'host' => '%env(DATABASE_HOST)%',
            'port' => '%env(DATABASE_PORT)%',
            'dbname' => '%env(DATABASE_DBNAME)%',
            'user' => '%env(DATABASE_USER)%',
            'password' => '%env(DATABASE_PASSWORD)%',
            'charset' => '%env(DATABASE_CHARSET)%',
            'types' => [
                'uuid' => UuidType::class,
            ],
        ],
        DoctrineExtension::ORM => [
            'auto_generate_proxy_classes' => true,
            'naming_strategy' => 'doctrine.orm.naming_strategy.underscore',
            'auto_mapping' => true,
            'mappings' => [
                'demo' => [
                    'is_bundle' => false,
                    'type' => 'annotation',
                    'dir' => __DIR__ . '/../../packages/demo/src/Entity',
                    'prefix' => 'Rector\Website\Demo\Entity',
                ],
                'contact' => [
                    'is_bundle' => false,
                    'type' => 'annotation',
                    'dir' => __DIR__ . '/../../src/Entity',
                    'prefix' => 'Rector\Website\Entity',
                ],
            ],
        ],
    ]);
};
