<?php

declare(strict_types=1);

namespace Rector\Website\Http\Controller\Ast;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt;
use Rector\CustomRules\SimpleNodeDumper;
use Rector\Website\Entity\AstRun;
use Rector\Website\PhpParser\ClickablePrinter;
use Rector\Website\PhpParser\NodeResolver\FocusedNodeResolver;
use Rector\Website\PhpParser\SimplePhpParser;
use Rector\Website\Repository\AstRunRepository;

final class AstDetailController extends Controller
{
    public function __construct(
        private readonly AstRunRepository $astRunRepository,
        private readonly SimplePhpParser $simplePhpParser,
        private readonly FocusedNodeResolver $focusedNodeResolver,
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

        $nodes = $this->simplePhpParser->parseString($astRun->getContent());

        if ($activeNodeId) {
            $focusedNode = $this->focusedNodeResolver->focus($nodes, $activeNodeId);
            $simpleNodeDump = SimpleNodeDumper::dump($focusedNode);

            $targetNodeClass = $this->resolveTargetNodeClass($focusedNode);
        } else {
            $simpleNodeDump = SimpleNodeDumper::dump($nodes);
            $targetNodeClass = null;
        }

        return \view('ast/ast_detail', [
            'page_title' => 'Play with AST',
            'matrix_vision' => $this->makeNodeClickable($nodes, $uuid, $activeNodeId),
            'simple_node_dump' => $simpleNodeDump,
            'active_node_id' => $activeNodeId,
            'target_node_class' => $targetNodeClass,
        ]);
    }

    /**
     * @param Node[] $nodes
     */
    private function makeNodeClickable(array $nodes, string $uuid, ?int $activeNodeId): string
    {
        $clickablePrinter = new ClickablePrinter($uuid, $activeNodeId);
        return $clickablePrinter->prettyPrint($nodes);
    }

    private function resolveTargetNodeClass(Node $node): string
    {
        if ($node instanceof Stmt\UseUse) {
            $parentNode = $node->getAttribute('parent');
            return $parentNode::class;
        }

        if ($node instanceof Stmt) {
            return $node::class;
        }

        // target one level up
        if ($node instanceof Identifier || $node instanceof Node\Name || $node instanceof Node\Expr\Variable) {
            $parentNode = $node->getAttribute('parent');
            return $this->resolveTargetNodeClass($parentNode);
        }

        if ($node instanceof Node\Expr\Variable) {
            $parentNode = $node->getAttribute('parent');

            // special case
            if ($parentNode instanceof Node\Param) {
                return $parentNode::class;
            }
        }

        return $node::class;
    }
}
