<?php

declare(strict_types=1);

namespace Rector\Website\RuleFilter;

use Rector\Website\RuleFilter\ValueObject\RectorSet;
use Rector\Website\RuleFilter\ValueObject\RuleMetadata;
use Rector\Website\Sets\RectorSetsTreeFactory;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RuleFilter
{
    public function __construct(
        private readonly RectorSetsTreeFactory $rectorSetsTreeFactory,
        private readonly MatchingScoreResolver $matchingScoreResolver,
    ) {
    }

    /**
     * @param RuleDefinition[] $ruleDefinitions
     * @return RuleMetadata[]
     */
    public function filter(array $ruleDefinitions, ?string $query): array
    {
        if ($query === null) {
            return [];
        }

        if (strlen($query) < 3) {
            return [];
        }

        $rectorSets = $this->rectorSetsTreeFactory->create();
        $richRuleDefinitions = [];

        foreach ($ruleDefinitions as $ruleDefinition) {
            $score = $this->matchingScoreResolver->resolve($ruleDefinition, $query);
            if ($score === 0) {
                continue;
            }

            $activeSets = $this->findRuleUsedSets($ruleDefinition, $rectorSets);
            $richRuleDefinitions[] = new RuleMetadata($ruleDefinition, $activeSets, $score);
        }

        usort(
            $richRuleDefinitions,
            function (RuleMetadata $firstRuleDefinitionAndRank, RuleMetadata $secondRuleDefinitionAndRank): int {
                return $secondRuleDefinitionAndRank->getRank() <=> $firstRuleDefinitionAndRank->getRank();
            }
        );

        // get max 10 results to keep page clear
        return array_slice($richRuleDefinitions, 0, 10);
    }

    /**
     * @param RectorSet[] $rectorSets
     * @return string[]
     */
    private function findRuleUsedSets(RuleDefinition $ruleDefinition, array $rectorSets): array
    {
        $activeSets = [];
        foreach ($rectorSets as $rectorSet) {
            if ($rectorSet->hasRule($ruleDefinition->getRuleClass())) {
                $activeSets[] = $rectorSet->getName();
            }
        }

        return $activeSets;
    }
}
