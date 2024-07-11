<?php

declare(strict_types=1);

namespace App\Sets;

use App\RuleFilter\ValueObject\RectorSet;
use Rector\Bridge\SetProviderCollector;
use Rector\Bridge\SetRectorsResolver;
use Rector\Set\Contract\SetInterface;

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
            // skip empty sets, usually for deprecated/future compatibility reasons
            if ($rectorSet->getRuleCount() === 0) {
                continue;
            }

            $rectorSetsByGroup[$rectorSet->getGroupName()][$rectorSet->getSlug()] = $rectorSet;
        }

        return $rectorSetsByGroup;
    }

    /**
     * @todo cache this on build to json somehow to avoid unnecessary calls
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
            /** @var SetInterface $set */
            $rectorClasses = $setRectorsResolver->resolveFromFilePath($set->getSetFilePath());
            $rectorSets[] = new RectorSet($set->getGroupName(), $set->getName(), $rectorClasses);
        }

        $this->rectorSets = $rectorSets;

        return $rectorSets;
    }
}
