<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enum\ComponentEvent;
use App\Enum\FindRuleQuery;
use App\FileSystem\RectorFinder;
use App\Logging\RectorFuleSearchLogger;
use App\RuleFilter\RuleFilter;
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
        /** @var RectorFinder $rectorFinder */
        $rectorFinder = app(RectorFinder::class);
        $ruleMetadatas = $rectorFinder->findCore();

        // wip
        // $communityRectors = $rectorFinder->findCommunity();

        // to trigger event in component javascript
        $this->dispatch(ComponentEvent::RULES_FILTERED);

        /** @var RuleFilter $ruleFilter */
        $ruleFilter = app(RuleFilter::class);
        $filteredRules = $ruleFilter->filter($ruleMetadatas, $this->query, $this->nodeType, $this->rectorSet);

        /** @var RectorFuleSearchLogger $searchLogger */
        $searchLogger = app(RectorFuleSearchLogger::class);

        // log only meaningful query, not a start of typing, to keep data clean
        if ($this->query === null || (strlen($this->query) > 3)) {
            $searchLogger->log($this->query, $this->nodeType, $this->rectorSet);
        }

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
}
