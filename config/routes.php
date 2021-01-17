<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symplify\Amnesia\ValueObject\Symfony\Routing;

return static function (RoutingConfigurator $routingConfigurator): void {
    $routingConfigurator->import(__DIR__ . '/../src/Controller', Routing::TYPE_ANNOTATION);
    $routingConfigurator->import(__DIR__ . '/../packages/blog/src/Controller', Routing::TYPE_ANNOTATION);
    $routingConfigurator->import(__DIR__ . '/../packages/demo/src/Controller', Routing::TYPE_ANNOTATION);
};
