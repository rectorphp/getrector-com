<?php

declare(strict_types=1);

$optionalFiles = [__DIR__ . '/../_ide_helper.php', __DIR__ . '/../_ide_helper_models.php'];

foreach ($optionalFiles as $optionalFile) {
    require_once $optionalFile;
}
