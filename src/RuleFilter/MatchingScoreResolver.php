<?php

declare(strict_types=1);

namespace Rector\Website\RuleFilter;

use Rector\Website\RuleFilter\ValueObject\RuleMetadata;

final class MatchingScoreResolver
{
    public function resolve(RuleMetadata $ruleMetadata, string $query): int
    {
        $score = 0;

        $queryParts = explode(' ', $query);
        foreach ($queryParts as $queryPart) {
            if (str_contains(strtolower($ruleMetadata->getRectorClass()), strtolower($queryPart))) {
                ++$score;
            }
        }

        foreach ($queryParts as $queryPart) {
            if (str_contains($ruleMetadata->getDescription(), $queryPart)) {
                ++$score;
            }
        }

        return $score;
    }
}
