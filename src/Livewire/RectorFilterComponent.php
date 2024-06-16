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

        // create select from hooked nodes
        $nodeTypeSelect = $this->createNodeTypeSelect($ruleMetadatas);

        $filteredRules = $ruleFilter->filter($ruleMetadatas, $this->query, $this->nodeType);

        return view('livewire.rector-filter-component', [
            'filteredRules' => $filteredRules,
            'nodeTypeSelectOptions' => $nodeTypeSelect,
            'isFilterActive' => ($this->query !== null && $this->query !== '') || ($this->nodeType !== null && $this->nodeType !== ''),
            'queryExamples' => self::QUERY_EXAMPLES,
        ]);
    }

    /**
     * @param RuleMetadata[] $ruleMetadatas
     * @return array<class-string<Node>>
     */
    private function resolveUsedNodesTypeFromMostUsed(array $ruleMetadatas): array
    {
        $usedNodeTypes = [];

        foreach ($ruleMetadatas as $ruleMetadata) {
            $usedNodeTypes = array_merge($usedNodeTypes, $ruleMetadata->getNodeTypes());
        }

        /** @var array<class-string<Node>, int> $nodeTypesToCount */
        $nodeTypesToCount = array_count_values($usedNodeTypes);
        arsort($nodeTypesToCount);

        return array_keys($nodeTypesToCount);
    }

    /**
     * @param RuleMetadata[] $ruleMetadatas
     * @return array<string, string>
     */
    private function createNodeTypeSelect(array $ruleMetadatas): array
    {
        $nodeTypes = $this->resolveUsedNodesTypeFromMostUsed($ruleMetadatas);

        // create select from these
        $nodeTypeSelect = [];
        foreach ($nodeTypes as $nodeType) {
            $nodeTypeSelect[$nodeType] = ucfirst(
                NodeTypeToHumanReadable::MAP[$nodeType] ?? $nodeType . '_todo_add_to_map'
            );
        }

        return $nodeTypeSelect;
    }
}
