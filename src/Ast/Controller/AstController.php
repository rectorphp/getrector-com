<?php

declare(strict_types=1);

namespace App\Ast\Controller;

use Illuminate\View\View;

final class AstController
{
    public function __invoke(): View
    {
        return \view('ast/ast', [
            'page_title' => 'Play with AST',
            'defaultPhpContents' => <<<'PHP'
<?php

if ($condition === 'demo') {
    return true;
}
PHP
        ]);
    }
}
