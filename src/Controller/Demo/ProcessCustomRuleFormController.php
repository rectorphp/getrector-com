<?php

declare(strict_types=1);

namespace Rector\Website\Controller\Demo;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Rector\Website\DemoRunner;
use Rector\Website\Entity\RectorRun;
use Rector\Website\Repository\RectorRunRepository;
use Rector\Website\Request\RectorRunFormRequest;
use Symfony\Component\Uid\Uuid;

final class ProcessCustomRuleFormController extends Controller
{
    public function __construct(
        private readonly DemoRunner $demoRunner,
        private readonly RectorRunRepository $rectorRunRepository,
    ) {
    }

    public function __invoke(RectorRunFormRequest $rectorRunFormRequest): RedirectResponse
    {
        // same for the demo run :)
        $rectorRun = new RectorRun(
            Uuid::v4(),
            $rectorRunFormRequest->getPhpContents(),
            $rectorRunFormRequest->getRunnableContents()
        );


        $this->demoRunner->processRectorRun($rectorRun);

        dump($rectorRun);
        die;

        $this->rectorRunRepository->save($rectorRun);

        return redirect()->action(CustomRuleDetailController::class, [
            'uuid' => $rectorRun->getUuid(),
        ]);
    }
}
