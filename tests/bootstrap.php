<?php

declare(strict_types=1);

// these files are generated only on "composer update"
// include them optionally in PHPStan
$optionalFiles = [__DIR__ . '/../_ide_helper.php', __DIR__ . '/../_ide_helper_models.php'];

foreach ($optionalFiles as $optionalFile) {
    if (file_exists($optionalFile)) {
        require_once $optionalFile;
    }
}
