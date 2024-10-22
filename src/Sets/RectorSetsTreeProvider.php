<?php

declare(strict_types=1);

namespace App\Sets;

use App\RuleFilter\ValueObject\RectorSet;
use Rector\Bridge\SetProviderCollector;
use Rector\Config\RectorConfig;
use Rector\Contract\Rector\RectorInterface;
use Rector\Set\Contract\SetInterface;
use RectorLaravel\Set\LaravelSetProvider;
use ReflectionProperty;
use Webmozart\Assert\Assert;

final class RectorSetsTreeProvider
{
    /**
     * Cache to keep it fast
     * @var RectorSet[]
     */
    private array $rectorSets = [];

    /**
     * Cache to keep it fast
     * @var RectorSet[]
     */
    private array $communityRectorSets = [];

    /**
     * @return RectorSet[]
     */
    public function provideByGroup(string $setGroup): array
    {
        $rectorSets = $this->provideCoreAndCommunity();

        $groupRectorSets = array_filter(
            $rectorSets,
            fn (RectorSet $rectorSet): bool => $rectorSet->getGroupName() === $setGroup
        );
        Assert::notEmpty($groupRectorSets);

        return $groupRectorSets;
    }

    /**
     * @return RectorSet[]
     */
    public function provideCoreAndCommunity(): array
    {
        return array_merge($this->provide(), $this->provideCommunity());
    }

    /**
     * @return RectorSet[]
     */
    public function provide(): array
    {
        if ($this->rectorSets !== []) {
            return $this->rectorSets;
        }

        $setProviderCollector = new SetProviderCollector();
        $rectorSets = $this->createRectorSetsFromSetProviders($setProviderCollector->provideSets());

        // cache per request
        $this->rectorSets = $rectorSets;

        return $rectorSets;
    }

    /**
     * @todo not relevant separation :)
     * @return RectorSet[]
     */
    public function provideCommunity(): array
    {
        if ($this->communityRectorSets !== []) {
            return $this->communityRectorSets;
        }

        $laravelSetProvider = new LaravelSetProvider();
        $communitySets = $laravelSetProvider->provide();

        $communityRectorSets = $this->createRectorSetsFromSetProviders($communitySets);

        // cache
        $this->communityRectorSets = $communityRectorSets;

        return $communityRectorSets;
    }

    /**
     * @return array<class-string<RectorInterface>>
     */
    public function resolveFromFilePath(string $configFilePath): array
    {
        Assert::fileExists($configFilePath);

        $rectorConfig = new RectorConfig();
        /** @var callable $configCallable */
        $configCallable = require $configFilePath;
        $configCallable($rectorConfig);

        // get tagged class-names
        $tagsReflectionProperty = new ReflectionProperty($rectorConfig, 'tags');
        $tags = $tagsReflectionProperty->getValue($rectorConfig);

        $rectorClasses = $tags[RectorInterface::class] ?? [];
        sort($rectorClasses);

        return array_unique($rectorClasses);
    }

    /**
     * @param SetInterface[] $sets
     * @return RectorSet[]
     */
    private function createRectorSetsFromSetProviders(array $sets): array
    {
        Assert::allIsInstanceOf($sets, SetInterface::class);
        $rectorSets = [];

        foreach ($sets as $set) {
            $rectorClasses = $this->resolveFromFilePath($set->getSetFilePath());
            $rectorSets[] = new RectorSet($set->getGroupName(), $set->getName(), $rectorClasses);
        }

        return $rectorSets;
    }
}
