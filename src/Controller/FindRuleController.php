<?php

declare(strict_types=1);

namespace App\Controller;

use App\FileSystem\RectorFinder;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

final class FindRuleController extends Controller
{
    public function __construct(
        private readonly RectorFinder $rectorFinder
    ) {
    }

    public function __invoke(): View
    {
        return \view('homepage/find_rule', [
            'page_title' => 'Find the best Rule',
            'ruleCount' => $this->rectorFinder->getRuleCount(),
            'codeMirror' => true,
            'livewireScripts' => true,
        ]);
    }
}
