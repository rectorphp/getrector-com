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

final class RectorFilterComponent extends Component
{
    #[Url]
    public ?string $query = null;

    #[Url]
    public ?string $rectorSet = null;

    #[Url]
    public ?string $nodeType = null;

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

        return view('livewire.rector-filter-component', [
            'filteredRules' => $filteredRules,
            'isFilterActive' => $this->isFilterActive(),
            'queryExamples' => FindRuleQuery::EXAMPLES,
            'rectorSetsByGroup' => $rectorSetsTreeProvider->provideGrouped(),
        ]);
    }

    private function isFilterActive(): bool
    {
        if ($this->query !== null && $this->query !== '') {
            return true;
        }

        if ($this->nodeType !== null && $this->nodeType !== '') {
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
        $ruleMetadatas = $rectorFinder->findCore();

        /** @var RuleFilter $ruleFilter */
        $ruleFilter = app(RuleFilter::class);

        return $ruleFilter->filter($ruleMetadatas, $this->query, $this->nodeType, $this->rectorSet);
    }

    private function logRuleSearch(): void
    {
        /** @var RectorFuleSearchLogger $rectorFuleSearchLogger */
        $rectorFuleSearchLogger = app(RectorFuleSearchLogger::class);

        // log only meaningful query, not a start of typing, to keep data clean
        $rectorFuleSearchLogger->log($this->query, $this->nodeType, $this->rectorSet);
    }
}
