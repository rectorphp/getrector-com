<?php

use Rector\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withConfiguredRule(SimplifyUselessVariableRector::class, [
        'only_direct_assign' => false,
    ]);
