<?php

declare(strict_types=1);

namespace Rector\Website\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use PhpParser\Node;
use Rector\Website\Enum\NodeTypeToHumanReadable;
use Rector\Website\FileSystem\RectorFinder;
use Rector\Website\RuleFilter\RuleFilter;
use Rector\Website\RuleFilter\ValueObject\RuleMetadata;

final class RectorFilterComponent extends Component
{
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

        // create select from hooked nodes
        $nodeTypeSelect = $this->createNodeTypeSelect($ruleMetadatas);

        return view('livewire.rector-filter-component', [
            'filteredRules' => $ruleFilter->filter($ruleMetadatas, $this->query, $this->nodeType),
            'nodeTypeSelectOptions' => $nodeTypeSelect,
        ]);
    }

    /**
     * @param RuleMetadata[] $ruleMetadatas
     * @return array<class-string<Node>, int>
     */
    private function resolveUsedNodesTypeToCount(array $ruleMetadatas): array
    {
        $usedNodeTypes = [];

        foreach ($ruleMetadatas as $ruleMetadata) {
            $usedNodeTypes = array_merge($usedNodeTypes, $ruleMetadata->getNodeTypes());
        }

        $nodeTypesToCount = array_count_values($usedNodeTypes);
        arsort($nodeTypesToCount);

        return $nodeTypesToCount;
    }

    /**
     * @param RuleMetadata[] $ruleMetadatas
     * @return array<string, string>
     */
    private function createNodeTypeSelect(array $ruleMetadatas): array
    {
        $nodeTypesToCount = $this->resolveUsedNodesTypeToCount($ruleMetadatas);

        // create select from these
        $nodeTypeSelect = [];
        foreach (array_keys($nodeTypesToCount) as $nodeType) {
            $nodeTypeSelect[$nodeType] = ucfirst(
                NodeTypeToHumanReadable::MAP[$nodeType] ?? $nodeType . '_todo_add_to_map'
            );
        }

        return $nodeTypeSelect;
    }
}
