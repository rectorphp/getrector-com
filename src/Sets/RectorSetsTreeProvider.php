<?php

declare(strict_types=1);

namespace App\Sets;

use App\RuleFilter\ValueObject\RectorSet;
use Rector\Bridge\SetProviderCollector;
use Rector\Bridge\SetRectorsResolver;

final class RectorSetsTreeProvider
{
    /**
     * Cache to keep it fast
     * @var RectorSet[]
     */
    private array $rectorSets = [];

    /**
     * @return array<string, RectorSet[]>
     */
    public function provideGrouped(): array
    {
        $rectorSetsByGroup = [];
        foreach ($this->rectorSets as $rectorSet) {
            $rectorSetsByGroup[$rectorSet->getGroupName()][] = $rectorSet;
        }

        return $rectorSetsByGroup;
    }

    /**
     * @return RectorSet[]
     */
    public function provide(): array
    {
        if ($this->rectorSets !== []) {
            return $this->rectorSets;
        }

        $rectorSets = [];

        $setProviderCollector = new SetProviderCollector();
        $setRectorsResolver = new SetRectorsResolver();

        foreach ($setProviderCollector->provideSets() as $set) {
            $rectorClasses = $setRectorsResolver->resolveFromFilePath($set->getSetFilePath());
            $rectorSets[] = new RectorSet($set->getGroupName(), $set->getName(), $rectorClasses);
        }

        $this->rectorSets = $rectorSets;

        return $rectorSets;
    }
}
