<?php

declare(strict_types=1);

namespace Rector\Website\RuleFilter;

use Rector\Website\RuleFilter\ValueObject\RuleMetadata;

final class RuleFilter
{
    public function __construct(
        private readonly MatchingScoreResolver $matchingScoreResolver,
    ) {
    }

    /**
     * @param RuleMetadata[] $ruleMetadatas
     * @return RuleMetadata[]
     */
    public function filter(array $ruleMetadatas, ?string $query): array
    {
        if ($query === null) {
            return [];
        }

        if (strlen($query) < 3) {
            return [];
        }

        foreach ($ruleMetadatas as $ruleMetadata) {
            $score = $this->matchingScoreResolver->resolve($ruleMetadata, $query);
            if ($score === 0) {
                continue;
            }

            $ruleMetadata->changeFilterScore($score);
        }

        usort(
            $ruleMetadatas,
            function (RuleMetadata $firstRuleMetadata, RuleMetadata $secondRuleMetadata): int {
                return $secondRuleMetadata->getFilterScore() <=> $firstRuleMetadata->getFilterScore();
            }
        );

        // get max 10 results to keep page clear
        return array_slice($ruleMetadatas, 0, 10);
    }
}
