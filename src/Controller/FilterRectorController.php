<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rector\Website\FileSystem\RectorFinder;
use Rector\Website\Sets\RectorSetsTreeFactory;
use Rector\Website\ValueObject\RichRuleDefinition;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class FilterRectorController extends Controller
{
    public function __construct(
        private readonly RectorFinder $rectorFinder,
        private readonly RectorSetsTreeFactory $rectorSetsTreeFactory,
    ) {
    }

    public function __invoke(Request $request): View
    {
        $ruleDefinitions = $this->rectorFinder->findCore();
        $richRuleDefinitions = [];

        $rectorSets = $this->rectorSetsTreeFactory->create();

        $isFilterEnabled = false;
        $query = $request->get('query');

        // at least 3 chars to invoke
        if (is_string($query) && strlen($query) > 2) {
            $isFilterEnabled = true;
            $queryParts = explode(' ', $query);

            foreach ($ruleDefinitions as $ruleDefinition) {
                $score = $this->computeSearchMatchingScore($queryParts, $ruleDefinition);
                if ($score === 0) {
                    continue;
                }

                // @todo match class against the tree
                $activeSets = [];
                foreach ($rectorSets as $rectorSet) {
                    if ($rectorSet->hasRule($ruleDefinition->getRuleClass())) {
                        $activeSets[] = $rectorSet->getName();
                    }
                }

                $richRuleDefinitions[] = new RichRuleDefinition($ruleDefinition, $activeSets, $score);
            }

            usort(
                $richRuleDefinitions,
                function (
                    RichRuleDefinition $firstRuleDefinitionAndRank,
                    RichRuleDefinition $secondRuleDefinitionAndRank
                ): int {
                    return $secondRuleDefinitionAndRank->getRank() <=> $firstRuleDefinitionAndRank->getRank();
                }
            );

            // get max 10 results
            $richRuleDefinitions = array_slice($richRuleDefinitions, 0, 10);
        }

        return \view('homepage/filter-rector', [
            'page_title' => 'Filter Rector',
            'coreRectorRules' => $richRuleDefinitions,
            'isFilterEnabled' => $isFilterEnabled,
        ]);
    }

    /**
     * @param string[] $queryParts
     */
    private function computeSearchMatchingScore(array $queryParts, RuleDefinition $ruleDefinition): int
    {
        $score = 0;

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
