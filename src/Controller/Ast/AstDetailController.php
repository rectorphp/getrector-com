<?php

declare(strict_types=1);

namespace Rector\Website\Controller\Ast;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Rector\Website\Entity\AstRun;
use Rector\Website\Repository\AstRunRepository;

final class AstDetailController extends Controller
{
    public function __construct(
        private readonly AstRunRepository $astRunRepository,
    ) {
    }

    public function __invoke(string $uuid, ?int $activeNodeId = null): View|RedirectResponse
    {
        $astRun = $this->astRunRepository->get($uuid);

        if (! $astRun instanceof AstRun) {
            return redirect_with_error(
                AstController::class,
                sprintf('Ast run "%s" was not found. Run code again to get new result', $uuid)
            );
        }

        return \view('ast/ast_detail', [
            'page_title' => 'Play with AST',
            'astRun' => $astRun,
        ]);
    }
}
