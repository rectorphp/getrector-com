<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enum\ComponentEvent;
use App\FileSystem\RectorFinder;
use App\Logging\SearchLogger;
use App\RuleFilter\RuleFilter;
use App\Sets\RectorSetsTreeProvider;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

final class RectorFilterComponent extends Component
{
    /**
     * @var string[]
     */
    private const QUERY_EXAMPLES = [
        'attributes',
        'add constant type',
        'remove tag',
        'add return type strict',
        'symfony rules',
    ];

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
        $rectorFinder->findCommunity();

        // to trigger event in component javascript
        $this->dispatch(ComponentEvent::RULES_FILTERED);

        /** @var RuleFilter $ruleFilter */
        $ruleFilter = app(RuleFilter::class);
        $filteredRules = $ruleFilter->filter($ruleMetadatas, $this->query, $this->nodeType, $this->rectorSet);

        /** @var SearchLogger $searchLogger */
        $searchLogger = app(SearchLogger::class);
        $searchLogger->log($this->query, $this->nodeType, $this->rectorSet);

        /** @var RectorSetsTreeProvider $rectorSetsTreeProvider */
        $rectorSetsTreeProvider = app(RectorSetsTreeProvider::class);

        return view('livewire.rector-filter-component', [
            'filteredRules' => $filteredRules,
            'isFilterActive' => $this->isFilterActive(),
            'queryExamples' => self::QUERY_EXAMPLES,
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
