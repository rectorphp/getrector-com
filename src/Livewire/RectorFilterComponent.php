<?php

declare(strict_types=1);

namespace Rector\Website\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use PhpParser\Node;
use Rector\Website\FileSystem\RectorFinder;
use Rector\Website\RuleFilter\RuleFilter;
use Rector\Website\Sets\RectorSetsTreeProvider;

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
        'remove unused property',
    ];

    #[Url]
    public ?string $query = null;

    #[Url]
    public ?string $set = null;

    /**
     * @var class-string<Node>|null
     */
    #[Url]
    public ?string $nodeType = null;

    public function render(): View
    {
        /** @var RectorFinder $rectorFinder */
        $rectorFinder = app(RectorFinder::class);
        $ruleMetadatas = $rectorFinder->findCore();

        // to trigger event in component javascript
        $this->dispatch('rules-filtered');

        /** @var RuleFilter $ruleFilter */
        $ruleFilter = app(RuleFilter::class);
        $filteredRules = $ruleFilter->filter($ruleMetadatas, $this->query, $this->nodeType);

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

        return $this->nodeType !== null && $this->nodeType !== '';
    }
}
