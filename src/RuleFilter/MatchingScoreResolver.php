<?php

declare(strict_types=1);

namespace Rector\Website\RuleFilter;

use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class MatchingScoreResolver
{
    public function resolve(RuleDefinition $ruleDefinition, string $query): int
    {
        $score = 0;

        $queryParts = explode(' ', $query);
        foreach ($queryParts as $queryPart) {
            if (str_contains(strtolower($ruleDefinition->getRuleClass()), strtolower($queryPart))) {
                ++$score;
            }
        }

        foreach ($queryParts as $queryPart) {
            if (str_contains($ruleDefinition->getDescription(), $queryPart)) {
                ++$score;
            }
        }

        return $score;
    }
}
