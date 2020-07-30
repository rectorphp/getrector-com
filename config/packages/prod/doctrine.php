<?php

declare(strict_types=1);

use Symfony\Component\Cache\DoctrineProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('doctrine', [
        'orm' => [
            'metadata_cache_driver' => [
                'type' => 'service',
                'id' => 'doctrine.system_cache_provider',
            ],
            'query_cache_driver' => [
                'type' => 'service',
                'id' => 'doctrine.system_cache_provider',
            ],
            'result_cache_driver' => [
                'type' => 'service',
                'id' => 'doctrine.result_cache_provider',
            ],
        ],
    ]);

    $services = $containerConfigurator->services();

    $services->set('doctrine.result_cache_provider', DoctrineProvider::class)
        ->private()
        ->args([ref('doctrine.result_cache_pool')]);

    $services->set('doctrine.system_cache_provider', DoctrineProvider::class)
        ->private()
        ->args([ref('doctrine.system_cache_pool')]);

    $containerConfigurator->extension('framework', [
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
