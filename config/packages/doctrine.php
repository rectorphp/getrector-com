<?php

declare(strict_types=1);

use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symplify\Amnesia\Functions\env;
use Symplify\Amnesia\ValueObject\Symfony\Extension\Doctrine\DBAL;
use Symplify\Amnesia\ValueObject\Symfony\Extension\Doctrine\Mapping;
use Symplify\Amnesia\ValueObject\Symfony\Extension\Doctrine\ORM;
use Symplify\Amnesia\ValueObject\Symfony\Extension\DoctrineExtension;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension(DoctrineExtension::NAME, [
        DoctrineExtension::DBAL => [
            DBAL::DRIVER => 'pdo_mysql',
            DBAL::SERVER_VERSION => '5.7',
            DBAL::HOST => env('DATABASE_HOST'),
            DBAL::PORT => env('DATABASE_PORT'),
            DBAL::DBNAME => env('DATABASE_DBNAME'),
            DBAL::USER => env('DATABASE_USER'),
            DBAL::PASSWORD => env('DATABASE_PASSWORD'),
            DBAL::CHARSET => env('DATABASE_CHARSET'),
            DBAL::TYPES => [
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
                    Mapping::DIR => __DIR__ . '/../../packages/Demo/Entity',
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
