<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('framework', ['secret' => '%env(APP_SECRET)%']);

    $containerConfigurator->extension('framework', [
        'csrf_protection' => [
            'enabled' => true,
        ],
    ]);

    $containerConfigurator->extension('framework', ['http_method_override' => true]);

    $containerConfigurator->extension('framework', ['trusted_hosts' => null]);

    $containerConfigurator->extension('framework', [
        'session' => [
            'handler_id' => null,
        ],
    ]);

    $containerConfigurator->extension('framework', [
        'esi' => [
            'enabled' => true,
        ],
    ]);

    $containerConfigurator->extension('framework', [
        'fragments' => [
            'enabled' => true,
        ],
    ]);

    $containerConfigurator->extension('framework', [
        'php_errors' => [
            'log' => true,
        ],
    ]);

    $containerConfigurator->extension('framework', ['ide' => 'phpstorm']);
};
