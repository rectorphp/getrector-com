<?php

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;

return RectorConfig::configure()
    // A. whole set
    ->withPreparedSets(typeDeclarations: true)
    // B. or few rules
    ->withRules([
        TypedPropertyFromAssignsRector::class
    ]);
