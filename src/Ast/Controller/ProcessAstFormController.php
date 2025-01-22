<?php

declare(strict_types=1);

namespace App\Ast\Controller;

use App\Ast\Entity\AstRun;
use App\Ast\Request\AstFormRequest;
use App\Repository\AstRunRepository;
use Illuminate\Http\RedirectResponse;

final class ProcessAstFormController
{
    public function __construct(
        private readonly AstRunRepository $astRunRepository
    ) {
    }

    public function __invoke(AstFormRequest $astFormRequest): RedirectResponse
    {
        $astRun = new AstRun();
        $astRun->setContent($astFormRequest->getPhpContents());

        $this->astRunRepository->save($astRun);

        return redirect()->action(AstDetailController::class, [
            'hash' => $astRun->getHash(),
        ]);
    }
}
