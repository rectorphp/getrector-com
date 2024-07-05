<?php

declare(strict_types=1);

namespace App\Controller;

use App\FileSystem\RectorFinder;
use App\RuleFilter\ValueObject\RuleMetadata;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

final class RuleDetailController extends Controller
{
    public function __construct(
        private readonly RectorFinder $rectorFinder
    ) {
    }

    public function __invoke(string $slug): View|RedirectResponse
    {
        $ruleMetadata = $this->rectorFinder->findBySlug($slug);

        if (! $ruleMetadata instanceof RuleMetadata) {
            // nothing found, get back
            return redirect()->action(FilterRectorController::class);
        }

        return \view('homepage/rule-detail', [
            'page_title' => $ruleMetadata->getRuleShortClass(),
            'ruleMetadata' => $ruleMetadata,
        ]);
    }
}
