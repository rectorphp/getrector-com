<?php

declare(strict_types=1);

use Rector\Website\Demo\ValueObject\Option;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use Symfony\Component\Security\Core\Security;
use function Symplify\Amnesia\Functions\env;
use Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../packages/*/config/*.php');

    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::DEMO_DIR, '%kernel.project_dir%/var/demo');

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

    $services->set(Security::class);
};
