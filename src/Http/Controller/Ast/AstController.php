<?php

declare(strict_types=1);

namespace Rector\Website\Http\Controller\Ast;

use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Rector\Website\Entity\AstRun;
use Symfony\Component\Uid\Uuid;

final class AstController extends Controller
{
    public function __invoke(): View
    {
        $astRun = new AstRun(Uuid::v4(), '');

        return \view('ast/ast', [
            'page_title' => 'Play with AST',
            'ast_run' => $astRun,
        ]);
    }
}
