<?php

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;

return static function (RectorConfig $rectorConfig): void {
    // A. run whole set
    $rectorConfig->sets([
        SetList::TYPE_DECLARATION,
    ]);

    // B. or single rule
    $rectorConfig->rule(TypedPropertyFromAssignsRector::class);
};
