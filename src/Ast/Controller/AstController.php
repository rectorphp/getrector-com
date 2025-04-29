<?php

declare(strict_types=1);

namespace App\Ast\Controller;

use Illuminate\Routing\Controller;
use Illuminate\View\View;

final class AstController extends Controller
{
    public function __invoke(): View
    {
        return \view('ast/ast', [
            'page_title' => 'Play with AST',

            'codeMirror' => true,
            'livewireScripts' => true,

            // not set yet
            'astRun' => null,
            'inputFormContents' => <<<'PHP'
<?php

if ($condition === 'demo') {
    return true;
}
PHP
        ]);
    }
}
