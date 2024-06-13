<?php

declare(strict_types=1);

namespace Rector\Website\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Nette\Utils\Strings;
use Rector\Website\FileSystem\RectorFinder;
use Rector\Website\RuleFilter\RuleFilter;

final class RectorFilterComponent extends Component
{
    #[Url]
    public ?string $query = null;

    public function render(): View
    {
        $rectorFinder = app(RectorFinder::class);
        $ruleMetadatas = $rectorFinder->findCore();

        $ruleFilter = app(RuleFilter::class);

        // to trigger event in component javascript
        $this->dispatch('rules-filtered');

        // create select from hooked nodes
        $usedNodeTypes = [];

        foreach ($ruleMetadatas as $ruleMetadata) {
            $usedNodeTypes = array_merge($usedNodeTypes, $ruleMetadata->getNodeTypes());
        }

        $nodeTypesToCount = array_count_values($usedNodeTypes);
        arsort($nodeTypesToCount);

        // create select from these
        $nodeTypeSelect = [];
        foreach ($nodeTypesToCount as $nodeType => $count) {
            $nodeTypeSelect[$nodeType] = $nodeType . 'human';
        }

        return view('livewire.rector-filter-component', [
            'filteredRules' => $ruleFilter->filter($ruleMetadatas, $this->query),
            'nodeTypeSelectOptions' => $nodeTypeSelect,
        ]);
    }
}
