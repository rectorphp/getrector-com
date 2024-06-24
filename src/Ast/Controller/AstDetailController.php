<?php

declare(strict_types=1);

namespace Rector\Website\Ast\Controller;

use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Rector\Website\Ast\Entity\AstRun;

final class AstDetailController extends Controller
{
    public function __invoke(string $hash): View
    {
        $astRun = AstRun::firstWhere('hash', $hash);

        return \view('ast/ast_detail', [
            'page_title' => 'Play with AST',
            'astRun' => $astRun,
        ]);
    }
}
