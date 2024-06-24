<?php

declare(strict_types=1);

namespace Rector\Website\Controller\Demo;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Rector\Website\Entity\RectorRun;
use Rector\Website\Repository\RectorRunRepository;

final class CustomRuleDetailController extends Controller
{
    public function __construct(
        private readonly RectorRunRepository $rectorRunRepository,
    ) {
    }

    public function __invoke(string $uuid): View|RedirectResponse
    {
        $rectorRun = $this->rectorRunRepository->get($uuid);
        if (! $rectorRun instanceof RectorRun) {
            return redirect_with_error(
                CustomRuleController::class,
                sprintf('Rector run "%s" was not found. Try to run code again for new result', $uuid)
            );
        }

        return \view('demo/custom-rule', [
            'page_title' => 'Design Custom Rule',
            'rectorRun' => $rectorRun,
        ]);
    }
}
