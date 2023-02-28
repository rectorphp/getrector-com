<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Rector\Website\Entity\RectorRun;
use Rector\Website\Enum\FlashType;
use Rector\Website\Enum\RouteName;
use Rector\Website\Repository\RectorRunRepository;
use Rector\Website\Utils\RectorVersionMetadata;
use Symfony\Component\Uid\Uuid;

final class DemoDetailController extends Controller
{
    public function __construct(
        private readonly RectorRunRepository $rectorRunRepository,
    ) {
    }

    public function __invoke(string $uuid): View|RedirectResponse
    {
        if (! Uuid::isValid($uuid)) {
            return to_route(RouteName::DEMO);
        }

        $rectorRun = $this->rectorRunRepository->get(Uuid::fromString($uuid));
        if (! $rectorRun instanceof RectorRun) {
            // item not found
            $errorMessage = sprintf('Rector run "%s" was not found. Try to run code again for new result', $uuid);
            session()
                ->flash(FlashType::ERROR, $errorMessage);

            return to_route(RouteName::DEMO);
        }

        return \view('demo/demo', [
            'page_title' => 'Try Rector Online',
            'rector_run' => $rectorRun,
            'rector_version_metadata' => new RectorVersionMetadata(),
        ]);
    }
}
