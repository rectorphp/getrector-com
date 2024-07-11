<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        \Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector::class
    ]);
