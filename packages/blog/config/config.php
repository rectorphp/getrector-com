<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SmartFileSystem\Finder\FinderSanitizer;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set('site_url', 'https://getrector.org');

    $services = $containerConfigurator->services();

    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure()
        ->bind('$projectDir', '%kernel.project_dir%');

    $services->load('Rector\Website\Blog\\', __DIR__ . '/../src/')
        ->exclude([__DIR__ . '/../src/ValueObject/*', __DIR__ . '/../src/DependencyInjection/*']);

    $services->set(ParsedownExtra::class, ParsedownExtra::class);

    $services->set(FinderSanitizer::class);
};
