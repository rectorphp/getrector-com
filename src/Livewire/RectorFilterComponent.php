<?php

declare(strict_types=1);

namespace Rector\Website\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Rector\Website\FileSystem\RectorFinder;
use Rector\Website\RuleFilter\RuleFilter;

final class RectorFilterComponent extends Component
{
    #[Url]
    public ?string $query = null;

    public function render(): View
    {
        $rectorFinder = app(RectorFinder::class);
        $coreRuleDefinitions = $rectorFinder->findCore();

        $ruleFilter = app(RuleFilter::class);

        return view('livewire.rector-filter-component', [
            'filteredRules' => $ruleFilter->filter($coreRuleDefinitions, $this->query),
        ]);
    }
}
