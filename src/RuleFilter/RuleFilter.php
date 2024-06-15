<?php

declare(strict_types=1);

namespace Rector\Website\RuleFilter;

use PhpParser\Node;
use Rector\Website\RuleFilter\ValueObject\RuleMetadata;

final class RuleFilter
{
    public function __construct(
        private readonly MatchingScoreResolver $matchingScoreResolver,
    ) {
    }

    /**
     * @param RuleMetadata[] $ruleMetadatas
     * @param class-string<Node>|null $nodeType
     * @return RuleMetadata[]
     */
    public function filter(array $ruleMetadatas, ?string $query, ?string $nodeType): array
    {
        if ($query === null) {
            return [];
        }

        if (strlen($query) < 3) {
            return [];
        }

        // filter by node type first
        if ($nodeType && is_a($nodeType, Node::class, true)) {
            $ruleMetadatas = array_filter(
                $ruleMetadatas,
                fn (RuleMetadata $ruleMetadata): bool => in_array($nodeType, $ruleMetadata->getNodeTypes())
            );
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

        usort(
            $filteredRuleMetadatas,
            function (RuleMetadata $firstRuleMetadata, RuleMetadata $secondRuleMetadata): int {
                return $secondRuleMetadata->getFilterScore() <=> $firstRuleMetadata->getFilterScore();
            }
        );

        // limit results to keep page clear
        return array_slice($filteredRuleMetadatas, 0, 5);
    }
}
