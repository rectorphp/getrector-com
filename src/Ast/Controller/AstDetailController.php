<?php

declare(strict_types=1);

namespace App\Ast\Controller;

use App\Ast\Entity\AstRun;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

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
