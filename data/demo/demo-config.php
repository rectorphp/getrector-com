<?php

use Rector\Config\RectorConfig;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    // A. run whole set
    $rectorConfig->sets([
        SetList::DEAD_CODE,
    ]);

    // B. or single rule
    $rectorConfig->rule(TypedPropertyRector::class);
};
