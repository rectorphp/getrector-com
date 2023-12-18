<?php

declare(strict_types=1);

namespace Rector\Website\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Rector\Website\DemoRunner;
use Rector\Website\Entity\RectorRun;
use Rector\Website\Http\Requests\DemoFormRequest;
use Rector\Website\Repository\RectorRunRepository;
use Symfony\Component\Uid\Uuid;

final class ProcessDemoFormController extends Controller
{
    public function __construct(
        private readonly DemoRunner $demoRunner,
        private readonly RectorRunRepository $rectorRunRepository,
    ) {
    }

    public function __invoke(DemoFormRequest $demoFormRequest): RedirectResponse
    {
        $rectorRun = new RectorRun(Uuid::v4(), $demoFormRequest->getPhpContents(), $demoFormRequest->getRectorConfig());

        $this->demoRunner->processRectorRun($rectorRun);
        $this->rectorRunRepository->save($rectorRun);

        return redirect()->action(DemoDetailController::class, [
            'uuid' => $rectorRun->getUuid(),
        ]);
    }
}
