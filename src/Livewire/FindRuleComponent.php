<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enum\ComponentEvent;
use App\Enum\FindRule\GroupName;
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
    public ?string $activeRectorSetGroup = null;

    public function render(): View
    {
        // to trigger event in component javascript
        $this->dispatch(ComponentEvent::RULES_FILTERED);

        $filteredRules = $this->getFilteredRuleMetadatas();

        $this->logRuleSearchIfUseful();

        /** @var RectorSetsTreeProvider $rectorSetsTreeProvider */
        $rectorSetsTreeProvider = app(RectorSetsTreeProvider::class);

        $rectorSets = $this->activeRectorSetGroup ? $rectorSetsTreeProvider->provideByGroup($this->activeRectorSetGroup) : [];

        return view('livewire.find-rule-component', [
            'filteredRules' => $filteredRules,
            'isFilterActive' => $this->isFilterActive(),
            'queryExamples' => FindRuleQuery::EXAMPLES,
            'rectorSets' => $rectorSets,
            'activeRectorSetGroup' => $this->activeRectorSetGroup,
            'rectorSetGroups' => [
                null => 'Any group',
                GroupName::PHP => 'PHP',
                GroupName::CORE => 'Core',
                // GroupName::ATTRIBUTES => 'Attributes',
                GroupName::SYMFONY => 'Symfony',
                'laravel' => 'Laravel (community)',
                GroupName::PHPUNIT => 'PHPUnit',
                GroupName::DOCTRINE => 'Doctrine',
                GroupName::TWIG => 'Twig',
            ],
        ]);
    }

    private function isFilterActive(): bool
    {
        if ($this->query !== null && $this->query !== '') {
            return true;
        }

        if ($this->activeRectorSetGroup !== null && $this->activeRectorSetGroup !== '') {
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

        return $ruleFilter->filter($ruleMetadatas, $this->query, $this->rectorSet, $this->activeRectorSetGroup);
    }

    private function logRuleSearchIfUseful(): void
    {
        /** @var RectorFuleSearchLogger $rectorFuleSearchLogger */
        $rectorFuleSearchLogger = app(RectorFuleSearchLogger::class);

        // log only meaningful query, not a start of typing, to keep data clean
        $rectorFuleSearchLogger->log($this->query, $this->activeRectorSetGroup, $this->rectorSet);
    }
}
