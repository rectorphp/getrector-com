<?php

declare(strict_types=1);

namespace App\RuleFilter\Bridge;

use Rector\Set\Contract\SetProviderInterface;
use Rector\Set\ValueObject\Set;
use RectorLaravel\Set\LaravelSetList;

/**
 * @see https://github.com/driftingly/rector-laravel/tree/main/config/sets
 */
final class LaravelSetProvider implements SetProviderInterface
{
    public function provide(): array
    {
        return [
            new Set(
                'Laravel Code Quality',
                'array/str functions to static calls',
                LaravelSetList::ARRAY_STR_FUNCTIONS_TO_STATIC_CALL
            ),
            // @todo
        ];
    }
}
