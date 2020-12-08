<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('framework', [
        'secret' => '%env(APP_SECRET)%',
        'csrf_protection' => [
            'enabled' => true,
        ],
        'http_method_override' => true,
        'trusted_hosts' => null,
        'session' => [
            'handler_id' => null,
        ],
        'esi' => [
            'enabled' => true,
        ],
        'fragments' => [
            'enabled' => true,
        ],
        'php_errors' => [
            'log' => true,
        ],
        'ide' => 'phpstorm',
    ]);
};
