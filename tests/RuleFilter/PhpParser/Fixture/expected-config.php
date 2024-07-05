<?php

use Rector\Config\RectorConfig;
use Rector\Php70\Rector\ClassMethod\Php4ConstructorRector;

return RectorConfig::configure()
    ->withRules([Php4ConstructorRector::class]);
