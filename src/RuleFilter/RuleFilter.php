<?php

declare(strict_types=1);

namespace App\RuleFilter;

use App\Exception\ShouldNotHappenException;
use App\RuleFilter\ValueObject\RectorSet;
use App\RuleFilter\ValueObject\RuleMetadata;
use App\Sets\RectorSetsTreeProvider;

final readonly class RuleFilter
{
    private const int MAX_RESULTS = 10;

    public function __construct(
        private MatchingScoreResolver $matchingScoreResolver,
    ) {
    }

    /**
     * @param RuleMetadata[] $ruleMetadatas
     * @return RuleMetadata[]
     */
    public function filter(array $ruleMetadatas, ?string $query, ?string $set, ?string $setGroup): array
    {
        $ruleMetadatas = $this->filterBySetGroup($ruleMetadatas, $setGroup);
        $ruleMetadatas = $this->filterBySet($ruleMetadatas, $set);
        $ruleMetadatas = $this->filterByQuery($ruleMetadatas, $query);

        $maxResults = self::MAX_RESULTS;
        if ($setGroup || $set) {
            $maxResults = 1000;
        }

        // limit results to keep page clear
        return array_slice($ruleMetadatas, 0, $maxResults);
    }

    /**
     * @param RuleMetadata[] $ruleMetadatas
     * @return RuleMetadata[]
     */
    private function filterByQuery(array $ruleMetadatas, ?string $query): array
    {
        // nothing to filter
        if ($query === null || strlen($query) < 3) {
            return $ruleMetadatas;
        }

        $filteredRuleMetadatas = [];
        foreach ($ruleMetadatas as $ruleMetadata) {
            $score = $this->matchingScoreResolver->resolve($ruleMetadata, $query);
            if ($score === 0) {
                continue;
            }

            $ruleMetadata->changeFilterScore($score);
            $filteredRuleMetadatas[] = $ruleMetadata;
        }

        // sort by score
        usort(
            $filteredRuleMetadatas,
            fn (RuleMetadata $firstRuleMetadata, RuleMetadata $secondRuleMetadata): int => $secondRuleMetadata->getFilterScore() <=> $firstRuleMetadata->getFilterScore()
        );

        return $filteredRuleMetadatas;
    }

    /**
     * @param RuleMetadata[] $ruleMetadatas
     * @return RuleMetadata[]
     */
    private function filterBySetGroup(array $ruleMetadatas, ?string $setGroup): array
    {
        if ($setGroup === '' || $setGroup === null) {
            return $ruleMetadatas;
        }

        return array_filter(
            $ruleMetadatas,
            fn (RuleMetadata $ruleMetadata): bool => $ruleMetadata->belongToSetGroup($setGroup)
        );
    }

    /**
     * @param RuleMetadata[] $ruleMetadatas
     * @return RuleMetadata[]
     */
    private function filterBySet(array $ruleMetadatas, ?string $set): array
    {
        if ($set === '' || $set === null) {
            return $ruleMetadatas;
        }

        /** @var RectorSetsTreeProvider $rectorSetsTreeProvider */
        $rectorSetsTreeProvider = app(RectorSetsTreeProvider::class);
        $coreAndCommunityRectorSets = $rectorSetsTreeProvider->provideCoreAndCommunity();

        // find set by slug
        $activeRectorSet = null;
        foreach ($coreAndCommunityRectorSets as $coreAndCommunityRectorSet) {
            if ($coreAndCommunityRectorSet->getSlug() === $set) {
                $activeRectorSet = $coreAndCommunityRectorSet;
            }
        }

        if (! $activeRectorSet instanceof RectorSet) {
            throw new ShouldNotHappenException('Missmatch of Rector set');
        }

        return array_filter(
            $ruleMetadatas,
            fn (RuleMetadata $ruleMetadata): bool => $activeRectorSet->hasRule($ruleMetadata->getRectorClass())
        );
    }
}
