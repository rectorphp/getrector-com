<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Website\Utils\Rector\NodeFactory\RouteGetCallFactory;
use Rector\Website\Utils\Tests\Rector\NodeFactory\SignaturePropertyFactory;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();
    $services->defaults()
        ->autowire();

    $services->set(RouteGetCallFactory::class);
    $services->set(SignaturePropertyFactory::class);
};
