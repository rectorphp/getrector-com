<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rector\Website\FileSystem\RectorFinder;
use Rector\Website\RuleFilter\RuleFilter;

final class FilterRectorController extends Controller
{
    public function __construct(
        private readonly RectorFinder $rectorFinder,
        private readonly RuleFilter $ruleFilter,
    ) {
    }

    public function __invoke(Request $request): View
    {
        $query = $request->get('query');

        $ruleDefinitions = $this->rectorFinder->findCore();
        $filteredRules = $this->ruleFilter->filter($ruleDefinitions, $query);

        return \view('homepage/filter-rector', [
            'page_title' => 'Filter Rector',
            'filteredRules' => $filteredRules,
        ]);
    }
}
