<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enum\ComponentEvent;
use App\Enum\FindRuleQuery;
use App\FileSystem\RectorFinder;
use App\Logging\RectorFuleSearchLogger;
use App\RuleFilter\RuleFilter;
use App\RuleFilter\ValueObject\RuleMetadata;
use App\Sets\RectorSetsTreeProvider;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

final class FindRuleComponent extends Component
{
    #[Url]
    public ?string $query = null;

    #[Url]
    public ?string $rectorSet = null;

    #[Url]
    public bool $isCommunityRules = false;

    public function render(): View
    {
        // wip
        // $communityRectors = $rectorFinder->findCommunity();

        // to trigger event in component javascript
        $this->dispatch(ComponentEvent::RULES_FILTERED);

        $filteredRules = $this->getFilteredRuleMetadatas();

        $this->logRuleSearch();

        /** @var RectorSetsTreeProvider $rectorSetsTreeProvider */
        $rectorSetsTreeProvider = app(RectorSetsTreeProvider::class);

        if ($this->isCommunityRules) {
            $rectorSetsByGroup = $rectorSetsTreeProvider->provideCommunityGrouped();
        } else {
            $rectorSetsByGroup = $rectorSetsTreeProvider->provideGrouped();
        }

        return view('livewire.find-rule-component', [
            'filteredRules' => $filteredRules,
            'isFilterActive' => $this->isFilterActive(),
            'queryExamples' => FindRuleQuery::EXAMPLES,
            'rectorSetsByGroup' => $rectorSetsByGroup,
            'communitySetsByGroup' => [],
        ]);
    }

    private function isFilterActive(): bool
    {
        if ($this->query !== null && $this->query !== '') {
            return true;
        }

        return $this->rectorSet !== null && $this->rectorSet !== '';
    }

    /**
     * @return RuleMetadata[]
     */
    private function getFilteredRuleMetadatas(): array
    {
        /** @var RectorFinder $rectorFinder */
        $rectorFinder = app(RectorFinder::class);
        $ruleMetadatas = array_merge($rectorFinder->findCore(), $rectorFinder->findCommunity());

        /** @var RuleFilter $ruleFilter */
        $ruleFilter = app(RuleFilter::class);

        return $ruleFilter->filter($ruleMetadatas, $this->query, $this->rectorSet);
    }

    private function logRuleSearch(): void
    {
        /** @var RectorFuleSearchLogger $rectorFuleSearchLogger */
        $rectorFuleSearchLogger = app(RectorFuleSearchLogger::class);

        // log only meaningful query, not a start of typing, to keep data clean
        $rectorFuleSearchLogger->log($this->query, $this->rectorSet);
    }
}
