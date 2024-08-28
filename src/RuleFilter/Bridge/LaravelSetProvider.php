<?php

declare(strict_types=1);

namespace App\RuleFilter\Bridge;

use App\Enum\FindRule\GroupName;
use Rector\Set\Contract\SetProviderInterface;
use Rector\Set\ValueObject\Set;
use RectorLaravel\Set\LaravelSetList;

/**
 * @todo should be part of the Laravel package, move later
 * @see https://github.com/driftingly/rector-laravel/tree/main/config/sets
 */
final class LaravelSetProvider implements SetProviderInterface
{
    public function provide(): array
    {
        return [
            new Set(
                GroupName::LARAVEL,
                'array/str func to static calls',
                LaravelSetList::ARRAY_STR_FUNCTIONS_TO_STATIC_CALL
            ),
            new Set(GroupName::LARAVEL, 'Code quality', LaravelSetList::LARAVEL_CODE_QUALITY),
            new Set(
                GroupName::LARAVEL,
                'Container strings to FQN types',
                LaravelSetList::LARAVEL_CONTAINER_STRING_TO_FULLY_QUALIFIED_NAME,
            ),
            // @todo fill the rest
        ];
    }
}
