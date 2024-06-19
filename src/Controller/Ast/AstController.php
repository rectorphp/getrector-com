<?php

declare(strict_types=1);

namespace Rector\Website\Controller\Ast;

use Illuminate\Routing\Controller;
use Illuminate\View\View;

final class AstController extends Controller
{
    public function __invoke(): View
    {
        return \view('ast/ast', [
            'page_title' => 'Play with AST',
        ]);
    }
}
