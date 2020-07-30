<?php

declare(strict_types=1);

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;
use Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set('local_demo_dir', '%kernel.project_dir%/var/demo');

    $parameters->set('host_demo_dir', '%env(HOST_DEMO_DIR)%');

    $parameters->set('rector_demo_docker_image', 'rector/rector-secured:latest');

    $parameters->set('demo_executable_path', '%kernel.project_dir%/bin/run-demo.sh');

    $parameters->set(
        'forbidden_functions',
        [
            'exec',
            'passthru',
            'shell_exec',
            'system',
            'proc_open',
            'popen',
            'curl_exec',
            'curl_multi_exec',
            'parse_ini_file',
            'show_source',
            'mail',
        ]
    );

    $parameters->set('demo_links', [[
        'label' => 'PHP 7.4 Typed Properties',
        'uuid' => 'c4f35db2-fe8d-4dde-bf3c-29c580dc60a1',
    ], [
        'label' => 'create_function()',
        'uuid' => '63843c0a-d24f-4e73-a9b4-5b96c82bdd2e',
    ], [
        'label' => 'Early Return',
        'uuid' => 'f9782921-32b6-4a0b-a9aa-779506d4ed3a',
    ], [
        'label' => 'Null Coalescing',
        'uuid' => '21f4705d-c654-4b45-9ca8-2e8b68f8ec54',
    ]]);

    $services = $containerConfigurator->services();

    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->load('Rector\Website\\', __DIR__ . '/../src/')
        ->exclude([__DIR__ . '/../src/GetRectorKernel.php']);

    $services->set(SymfonyStyleFactory::class);

    $services->set(SymfonyStyle::class)
        ->factory([ref(SymfonyStyleFactory::class), 'create']);
};
