<?php

declare(strict_types=1);

use Rector\Website\Demo\ValueObject\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\SmartFileSystem\Finder\FinderSanitizer;
use Symplify\SmartFileSystem\SmartFileSystem;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::SITE_URL, 'https://getrector.org');

    $services = $containerConfigurator->services();

    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->load('Rector\Website\Blog\\', __DIR__ . '/../src/')
        ->exclude([__DIR__ . '/../src/ValueObject']);

    $services->set(ParsedownExtra::class, ParsedownExtra::class);

    $services->set(FinderSanitizer::class);
    $services->set(SmartFileSystem::class);

    $services->set(ParameterProvider::class);
};
