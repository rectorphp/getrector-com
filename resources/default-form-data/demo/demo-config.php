<?php

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    // A. whole set
    ->withPreparedSets(typeDeclarations: true)
    // B. or few rules
    ->withRules([
        TypedPropertyFromAssignsRector::class
    ])
    // demonstrate specific PHP version
    ->withPhpVersion(PhpVersion::PHP_84);
