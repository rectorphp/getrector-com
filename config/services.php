<?php

declare(strict_types=1);

use Rector\Website\ValueObject\Option;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;
use Symplify\SmartFileSystem\Finder\FinderSanitizer;
use Symplify\SmartFileSystem\SmartFileSystem;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::DEMO_DIR, '%kernel.project_dir%/var/demo');

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

    $services->set(PrivatesAccessor::class);

    $services->set(SymfonyStyleFactory::class);
    $services->set(SymfonyStyle::class)
        ->factory([service(SymfonyStyleFactory::class), 'create']);

    $services->set(ParsedownExtra::class, ParsedownExtra::class);

    $services->set(FinderSanitizer::class);
    $services->set(SmartFileSystem::class);

    $services->set(ParameterProvider::class)
        ->arg('$container', service('service_container'));
};
