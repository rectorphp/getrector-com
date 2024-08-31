<?php

declare(strict_types=1);

namespace App\RuleFilter\Bridge;

use App\Enum\FindRule\GroupName;
use Rector\Set\Contract\SetProviderInterface;
use Rector\Set\ValueObject\Set;
use RectorLaravel\Set\LaravelSetList;
use RectorLaravel\Set\Packages\Livewire\LivewireSetList;

/**
 * @todo should be part of the Laravel package, move later
 * @see https://github.com/driftingly/rector-laravel/tree/main/config/sets
 */
final class LaravelSetProvider implements SetProviderInterface
{
    /**
     * @var string[]
     */
    private static array $laravelFiveVersions = [
        LaravelSetList::LARAVEL_50,
        LaravelSetList::LARAVEL_51,
        LaravelSetList::LARAVEL_52,
        LaravelSetList::LARAVEL_53,
        LaravelSetList::LARAVEL_54,
        LaravelSetList::LARAVEL_55,
        LaravelSetList::LARAVEL_56,
        LaravelSetList::LARAVEL_57,
        LaravelSetList::LARAVEL_58,
    ];

    /**
     * @var string[]
     */
    private static array $laravelPostFiveVersions = [
        LaravelSetList::LARAVEL_60,
        LaravelSetList::LARAVEL_70,
        LaravelSetList::LARAVEL_80,
        LaravelSetList::LARAVEL_90,
        LaravelSetList::LARAVEL_100,
        LaravelSetList::LARAVEL_110,
    ];

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
            new Set(
                GroupName::LARAVEL,
                'Code Quality for Laravel',
                LaravelSetList::LARAVEL_CODE_QUALITY,
            ),
            new Set(
                GroupName::LARAVEL,
                'Replaces If statements with helpers',
                LaravelSetList::LARAVEL_IF_HELPERS,
            ),
            new Set(
                GroupName::LARAVEL,
                'Replace facades with service injection',
                LaravelSetList::LARAVEL_STATIC_TO_INJECTION,
            ),
            new Set(
                GroupName::LARAVEL,
                'Rename Alias to FQN Classes',
                LaravelSetList::LARAVEL_FACADE_ALIASES_TO_FULL_NAMES,
            ),
            new Set(
                GroupName::LARAVEL,
                'Replace Magic Methods to Query Builder',
                LaravelSetList::LARAVEL_ELOQUENT_MAGIC_METHOD_TO_QUERY_BUILDER,
            ),
            new Set(
                GroupName::LARAVEL,
                'Upgrade Legacy Factories to Modern Factories',
                LaravelSetList::LARAVEL_LEGACY_FACTORIES_TO_CLASSES,
            ),
            new Set(
                GroupName::LARAVEL,
                'Livewire 3.0',
                LivewireSetList::LIVEWIRE_30,
            ),
            ...$this->getLaravelVersions(),
        ];
    }

    /**
     * @return Set[]
     */
    private function getLaravelVersions(): array
    {
        $versions = [];

        foreach (self::$laravelFiveVersions as $index => $version) {
            $versions[] = new Set(
                GroupName::LARAVEL,
                'Laravel Framework 5.' . $index,
                $version,
            );
        }

        foreach (self::$laravelPostFiveVersions as $index => $version) {
            $versions[] = new Set(
                GroupName::LARAVEL,
                'Laravel Framework ' . ($index + 6) . '.0',
                $version,
            );
        }

        return $versions;
    }
}
