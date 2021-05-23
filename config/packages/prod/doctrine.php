<?php

declare(strict_types=1);

use Symfony\Component\Cache\DoctrineProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\Amnesia\ValueObject\Symfony\Extension\FrameworkExtension;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('doctrine', [
        'orm' => [
            'metadata_cache_driver' => [
                'type' => 'pool',
                'id' => 'doctrine.system_cache_pool',
            ],
            'query_cache_driver' => [
                'type' => 'pool',
                'id' => 'doctrine.system_cache_pool',
            ],
            'result_cache_driver' => [
                'type' => 'pool',
                'id' => 'doctrine.result_cache_pool',
            ],
        ],
    ]);

    $services = $containerConfigurator->services();

    $services->set('doctrine.result_cache_provider', DoctrineProvider::class)
        ->private()
        ->args([service('doctrine.result_cache_pool')]);

    $services->set('doctrine.system_cache_provider', DoctrineProvider::class)
        ->private()
        ->args([service('doctrine.system_cache_pool')]);

    $containerConfigurator->extension(FrameworkExtension::NAME, [
        'cache' => [
            'pools' => [
                'doctrine.result_cache_pool' => [
                    'adapter' => 'cache.app',
                ],
                'doctrine.system_cache_pool' => [
                    'adapter' => 'cache.system',
                ],
            ],
        ],
    ]);

};
