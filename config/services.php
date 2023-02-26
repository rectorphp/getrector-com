<?php

declare(strict_types=1);

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $services = $containerConfigurator->services();
    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->load('Rector\Website\\', __DIR__ . '/../src/')
        ->exclude([
            __DIR__ . '/../src/GetRectorKernel.php',
            __DIR__ . '/../src/ValueObject',
            __DIR__ . '/../src/Entity',
        ]);

    $services->set(SymfonyStyleFactory::class);
    $services->set(SymfonyStyle::class)
        ->factory([service(SymfonyStyleFactory::class), 'create']);

    $services->set(ParsedownExtra::class, ParsedownExtra::class);
};
