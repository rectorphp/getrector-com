<?php

declare(strict_types=1);

namespace Rector\Website\Controller\Ast;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Rector\Website\Entity\AstRun;
use Rector\Website\Repository\AstRunRepository;
use Rector\Website\Request\AstFormRequest;
use Symfony\Component\Uid\Uuid;

final class ProcessAstFormController extends Controller
{
    public function __construct(
        private AstRunRepository $astRunRepository
    ) {
    }

    public function __invoke(AstFormRequest $astFormRequest): RedirectResponse
    {
        $astRun = new AstRun(Uuid::v4(), $astFormRequest->getPhpContents());
        $this->astRunRepository->save($astRun);

        return redirect()->action(AstDetailController::class, [
            'uuid' => $astRun->getUuid(),
        ]);
    }
}
