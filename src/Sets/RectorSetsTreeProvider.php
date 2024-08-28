<?php

declare(strict_types=1);

namespace App\Sets;

use App\RuleFilter\Bridge\LaravelSetProvider;
use App\RuleFilter\ValueObject\RectorSet;
use Rector\Bridge\SetProviderCollector;
use Rector\Bridge\SetRectorsResolver;
use Rector\Set\Contract\SetInterface;
use Webmozart\Assert\Assert;

final class RectorSetsTreeProvider
{
    /**
     * Cache to keep it fast
     * @var RectorSet[]
     */
    private array $rectorSets = [];

    /**
     * @var RectorSet[]
     */
    private array $communityRectorSets = [];

    /**
     * @return array<string, RectorSet[]>
     */
    public function provideGrouped(): array
    {
        return $this->groupSets($this->rectorSets);
    }

    /**
     * @return array<string, RectorSet[]>
     */
    public function provideCommunityGrouped(): array
    {
        return $this->groupSets($this->provideCommunityRectorSets());
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
     * @return RectorSet[]
     */
    public function provideCommunityRectorSets(): array
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
     * @param RectorSet[]  $rectorSets
     * @return array<string, RectorSet[]>
     */
    private function groupSets(array $rectorSets): array
    {
        $rectorSetsByGroup = [];

        foreach ($rectorSets as $rectorSet) {
            // skip empty sets, usually for deprecated/future compatibility reasons
            if ($rectorSet->getRuleCount() === 0) {
                continue;
            }

            $rectorSetsByGroup[$rectorSet->getGroupName()][$rectorSet->getSlug()] = $rectorSet;
        }

        Assert::notEmpty($rectorSetsByGroup);

        return $rectorSetsByGroup;
    }

    /**
     * @param SetInterface[] $sets
     * @return RectorSet[]
     */
    private function createRectorSetsFromSetProviders(array $sets): array
    {
        Assert::allIsInstanceOf($sets, SetInterface::class);

        $setRectorsResolver = new SetRectorsResolver();
        $rectorSets = [];

        foreach ($sets as $set) {
            $rectorClasses = $setRectorsResolver->resolveFromFilePath($set->getSetFilePath());
            $rectorSets[] = new RectorSet($set->getGroupName(), $set->getName(), $rectorClasses);
        }

        return $rectorSets;
    }
}
