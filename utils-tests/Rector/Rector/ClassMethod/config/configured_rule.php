<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->removeUnusedImports();

    $rectorConfig->import(__DIR__ . '/../../../../../utils/config/rector_services.php');

    $rectorConfig->ruleWithConfiguration(\Rector\Website\Utils\Rector\Rector\ClassMethod\SymfonyRouteAttributesToLaravelRouteFileRector::class, [
        \Rector\Website\Utils\Rector\Rector\ClassMethod\SymfonyRouteAttributesToLaravelRouteFileRector::ROUTES_FILE_PATH => __DIR__ . '/dumped_routes.php',
    ]);
};
