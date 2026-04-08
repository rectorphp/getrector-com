<?php

namespace App\Controller;

use App\FileSystem\RectorFinder;
use App\RuleFilter\ValueObject\RuleMetadata;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FallbackController extends Controller
{
    public function __construct(
        private readonly RectorFinder $rectorFinder
    ) {
    }

    public function __invoke(Request $request)
    {
        $slug = str($request->path())->kebab()->toString();
        $ruleMetadata = $this->rectorFinder->findBySlug($slug);

        if (! $ruleMetadata instanceof RuleMetadata) {
            abort(404);
        }

        return redirect(status: 301)->action(RuleDetailController::class, [
            'slug' => $ruleMetadata->getSlug(),
        ]);
    }
}
