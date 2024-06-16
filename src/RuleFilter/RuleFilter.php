<?php

declare(strict_types=1);

namespace Rector\Website\RuleFilter;

use PhpParser\Node;
use Rector\Website\RuleFilter\ValueObject\RuleMetadata;

final class RuleFilter
{
    /**
     * @var int
     */
    private const MAX_RESULTS = 5;

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
        $ruleMetadatas = $this->filterByNodeTypeFirst($ruleMetadatas, $nodeType);
        $ruleMetadatas = $this->filterByQuery($ruleMetadatas, $query);

        // limit results to keep page clear
        return array_slice($ruleMetadatas, 0, self::MAX_RESULTS);
    }

    /**
     * @param RuleMetadata[] $ruleMetadatas
     * @return RuleMetadata[]
     */
    private function filterByNodeTypeFirst(array $ruleMetadatas, ?string $nodeType): array
    {
        if ($nodeType === null || ! is_a($nodeType, Node::class, true)) {
            return $ruleMetadatas;
        }

        return array_filter(
            $ruleMetadatas,
            fn (RuleMetadata $ruleMetadata): bool => in_array($nodeType, $ruleMetadata->getNodeTypes())
        );
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
            function (RuleMetadata $firstRuleMetadata, RuleMetadata $secondRuleMetadata): int {
                return $secondRuleMetadata->getFilterScore() <=> $firstRuleMetadata->getFilterScore();
            }
        );

        return $filteredRuleMetadatas;
    }
}
