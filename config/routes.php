<?php

declare(strict_types=1);

use Rector\Website\ValueObject\Symfony\RoutingExtension;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routingConfigurator): void {
    $routingConfigurator->import(__DIR__ . '/../src/Controller', RoutingExtension::ANNOTATION_TYPE);
    $routingConfigurator->import(__DIR__ . '/../packages/blog/src/Controller', RoutingExtension::ANNOTATION_TYPE);
    $routingConfigurator->import(__DIR__ . '/../packages/demo/src/Controller', RoutingExtension::ANNOTATION_TYPE);
};
