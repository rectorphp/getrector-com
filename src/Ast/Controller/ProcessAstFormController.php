<?php

declare(strict_types=1);

namespace Rector\Website\Ast\Controller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Rector\Website\Ast\Entity\AstRun;
use Rector\Website\Ast\Request\AstFormRequest;

final class ProcessAstFormController extends Controller
{
    public function __invoke(AstFormRequest $astFormRequest): RedirectResponse
    {
        $astRun = new AstRun();
        $astRun->setContent($astFormRequest->getPhpContents());
        $astRun->save();

        return redirect()->action(AstDetailController::class, [
            'hash' => $astRun->getHash(),
        ]);
    }
}
