<?php

declare(strict_types=1);

use Rector\Website\Demo\ValueObject\Option;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
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

    $services = $containerConfigurator->services();

    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->load('Rector\Website\\', __DIR__ . '/../src/')
        ->exclude(
            [__DIR__ . '/../src/GetRectorKernel.php', __DIR__ . '/../src/ValueObject', __DIR__ . '/../src/Entity']
        );

    $services->set(SymfonyStyleFactory::class);

    $services->set(PrivatesAccessor::class);

    $services->set(SymfonyStyle::class)
        ->factory([service(SymfonyStyleFactory::class), 'create']);
};
