<?php

declare(strict_types=1);

namespace Rector\Website\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Rector\Website\Enum\NodeTypeToHumanReadable;
use Rector\Website\FileSystem\RectorFinder;
use Rector\Website\RuleFilter\RuleFilter;

final class RectorFilterComponent extends Component
{
    /**
     * @var string[]
     */
    private const QUERY_EXAMPLES = ['add constant type', 'add return type strict', 'remove unused property'];

    #[Url]
    public ?string $query = null;

    #[Url]
    public ?string $nodeType = null;

    public function render(): View
    {
        $rectorFinder = app(RectorFinder::class);
        $ruleMetadatas = $rectorFinder->findCore();

        $ruleFilter = app(RuleFilter::class);

        // to trigger event in component javascript
        $this->dispatch('rules-filtered');

        $filteredRules = $ruleFilter->filter($ruleMetadatas, $this->query, $this->nodeType);

        return view('livewire.rector-filter-component', [
            'filteredRules' => $filteredRules,
            'isFilterActive' => $this->isFilterActive(),
            'queryExamples' => self::QUERY_EXAMPLES,
            'groupedNodeTypeSelectOptions' => NodeTypeToHumanReadable::SELECT_ITEMS_BY_GROUP,
        ]);
    }

    private function isFilterActive(): bool
    {
        if ($this->query !== null && $this->query !== '') {
            return true;
        }

        return $this->nodeType !== null && $this->nodeType !== '';
    }
}
