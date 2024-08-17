<?php

declare(strict_types=1);

return \Rector\Config\RectorConfig::configure()
    ->withPaths([__DIR__ . '/src'])
    __WITH_PHP_SETS
    __WITH_TYPE_DECLARATION_LEVEL
    __WITH_DEAD_CODE_LEVEL
    __WITH_CODE_QUALITY_LEVEL
