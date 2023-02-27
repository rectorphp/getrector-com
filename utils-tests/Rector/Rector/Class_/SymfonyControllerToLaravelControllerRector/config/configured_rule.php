<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Website\Utils\Rector\Rector\Class_\SymfonyControllerToLaravelControllerRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->removeUnusedImports();

    $rectorConfig->import(__DIR__ . '/../../../../../../utils/config/rector_services.php');

    $rectorConfig->rule(SymfonyControllerToLaravelControllerRector::class);
};
