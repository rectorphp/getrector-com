<?php

declare(strict_types=1);

use Rector\Website\Demo\ValueObject\Option;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;
use Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::LOCAL_DEMO_DIR, '%kernel.project_dir%/var/demo');
    $parameters->set(Option::HOST_DEMO_DIR, '%env(HOST_DEMO_DIR)%');
    $parameters->set(Option::RECTOR_DEMO_DOCKER_IMAGE, 'rector/rector-secured:latest');
    $parameters->set(Option::DEMO_EXECUTABLE_PATH, '%kernel.project_dir%/bin/run-demo.sh');

    $parameters->set(Option::FORBIDDEN_FUNCTIONS, [
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
    ]);

    $parameters->set(Option::DEMO_LINKS, [
        [
            'label' => 'PHP 7.4 Typed Properties',
            'uuid' => '19ac6368-a647-43eb-a762-d16abe61dfff',
        ], [
            'label' => 'create_function()',
            'uuid' => '90fe1d8c-affc-499c-988e-cc746a242dc5',
        ], [
            'label' => 'Early Return',
            'uuid' => '950be432-0e91-4bbf-837e-080f0329d9d4',
        ], [
            'label' => 'Null Coalescing',
            'uuid' => '81d6c6c4-a8e1-4eee-a1fb-24599aee4e5e',
        ],
    ]);

    $services = $containerConfigurator->services();

    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->load('Rector\Website\\', __DIR__ . '/../src/')
        ->exclude([__DIR__ . '/../src/GetRectorKernel.php']);

    $services->set(SymfonyStyleFactory::class);

    $services->set(PrivatesAccessor::class);

    $services->set(SymfonyStyle::class)
        ->factory([ref(SymfonyStyleFactory::class), 'create']);
};
