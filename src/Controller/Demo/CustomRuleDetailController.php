<?php

declare(strict_types=1);

namespace App\Controller\Demo;

use App\Entity\RectorRun;
use App\Repository\RectorRunRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

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
