<?php

declare(strict_types=1);

namespace Rector\Website\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rector\Website\Entity\RectorRun;
use Symfony\Component\Uid\Uuid;

final class ProcessAstFormController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        dd($request);
        die;

        $rectorRun = new RectorRun(Uuid::v4(), $demoFormRequest->getPhpContents(), $demoFormRequest->getRectorConfig());

        $this->demoRunner->processRectorRun($rectorRun);
        $this->rectorRunRepository->save($rectorRun);

        return redirect()->action(DemoDetailController::class, [
            'uuid' => $rectorRun->getUuid(),
        ]);
    }
}
