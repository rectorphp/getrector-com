<?php

declare(strict_types=1);

namespace App\Ast\Controller;

use App\Repository\AstRunRepository;
use Illuminate\View\View;

final readonly class AstDetailController
{
    public function __construct(
        private AstRunRepository $astRunRepository
    ) {
    }

    public function __invoke(string $hash): View
    {
        $astRun = $this->astRunRepository->findByHash($hash);

        return \view('ast/ast', [
            'page_title' => 'Play with AST',
            'astRun' => $astRun,
            'inputFormContents' => $astRun->getContent(),
            'codeMirror' => true,
            'livewireScripts' => true,
        ]);
    }
}
