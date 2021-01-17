<?php

declare(strict_types=1);

use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\Amnesia\ValueObject\Symfony\Extension\Doctrine\Mapping;
use Symplify\Amnesia\ValueObject\Symfony\Extension\Doctrine\ORM;
use Symplify\Amnesia\ValueObject\Symfony\Extension\DoctrineExtension;

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
            ORM::AUTO_GENERATE_PROXY_CLASSES => true,
            ORM::NAMING_STRATEGY => 'doctrine.orm.naming_strategy.underscore',
            ORM::AUTO_MAPPING => true,
            ORM::MAPPINGS => [
                'demo' => [
                    Mapping::IS_BUNDLE => false,
                    Mapping::TYPE => Mapping::TYPE_ANNOTATION,
                    Mapping::DIR => __DIR__ . '/../../packages/demo/src/Entity',
                    Mapping::PREFIX => 'Rector\Website\Demo\Entity',
                ],
                'contact' => [
                    Mapping::IS_BUNDLE => false,
                    Mapping::TYPE => Mapping::TYPE_ANNOTATION,
                    Mapping::DIR => __DIR__ . '/../../src/Entity',
                    Mapping::PREFIX => 'Rector\Website\Entity',
                ],
            ],
        ],
    ]);
};
