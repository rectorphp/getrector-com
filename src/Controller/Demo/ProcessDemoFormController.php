<?php

declare(strict_types=1);

namespace App\Controller\Demo;

use App\DemoRunner;
use App\Entity\RectorRun;
use App\Repository\RectorRunRepository;
use App\Request\RectorRunFormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Symfony\Component\Uid\Uuid;

final class ProcessDemoFormController extends Controller
{
    public function __construct(
        private readonly DemoRunner $demoRunner,
        private readonly RectorRunRepository $rectorRunRepository,
    ) {
    }

    public function __invoke(RectorRunFormRequest $rectorRunFormRequest): RedirectResponse
    {
        $rectorRun = new RectorRun(
            Uuid::v4(),
            $rectorRunFormRequest->getPhpContents(),
            $rectorRunFormRequest->getRunnableContents()
        );

        $this->demoRunner->processRectorRun($rectorRun);
        $this->rectorRunRepository->save($rectorRun);

        return redirect()->action(DemoDetailController::class, [
            'uuid' => $rectorRun->getUuid(),
        ]);
    }
}
