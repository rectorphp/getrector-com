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
        $kebab = str($slug)->kebab()->toString();
        $ruleMetadata = $this->rectorFinder->findBySlug($kebab);

        if (! $ruleMetadata instanceof RuleMetadata) {
            // nothing found, get back
            return redirect()->action(FindRuleController::class);
        }

        if ($kebab !== $slug) {
            return redirect(status: 301)->action(RuleDetailController::class, [
                'slug' => $kebab,
            ]);
        }

        return \view('homepage/rule_detail', [
            'page_title' => $ruleMetadata->getRuleShortClass(),
            'ruleMetadata' => $ruleMetadata,
            'codeMirror' => true,
        ]);
    }
}
