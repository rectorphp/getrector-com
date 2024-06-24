<?php

declare(strict_types=1);

namespace App\Ast\Controller;

use App\Ast\Entity\AstRun;
use App\Ast\Request\AstFormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

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
