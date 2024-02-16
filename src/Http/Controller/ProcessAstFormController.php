<?php

declare(strict_types=1);

namespace Rector\Website\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Rector\Website\Entity\AstRun;
use Rector\Website\Http\Requests\AstFormRequest;

final class ProcessAstFormController extends Controller
{
    public function __invoke(AstFormRequest $astFormRequest): RedirectResponse
    {
        $astRun = new AstRun($astFormRequest->getPhpContents());

        $this->rectorRunRepository->save($astRun);

        return redirect()->action(DemoDetailController::class, [
            'uuid' => $astRun->getUuid(),
        ]);
    }
}
