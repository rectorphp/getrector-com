<?php

declare(strict_types=1);

namespace Rector\Website\Http\Controller\Ast;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use Rector\CustomRules\SimpleNodeDumper;
use Rector\Website\Entity\AstRun;
use Rector\Website\Enum\AttributeKey;
use Rector\Website\PhpParser\ClickableAstPrinter;
use Rector\Website\PhpParser\NodeVisior\NodeMarkerNodeVisitor;
use Rector\Website\PhpParser\SimplePhpParser;
use Rector\Website\Repository\AstRunRepository;

final class AstDetailController extends Controller
{
    public function __construct(
        private readonly AstRunRepository $astRunRepository,
        private readonly SimplePhpParser $simplePhpParser,
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

        // @todo fill the node that is being clicked, e.g. based on the node hash
        // + colorize
        $matrixVision = $this->makeNodeClickable($nodes, $uuid);

        $nodes = $this->focusNodes($activeNodeId, $nodes);

        $simpleNodeDump = SimpleNodeDumper::dump($nodes);

        return \view('ast/ast_detail', [
            'page_title' => 'Play with AST',
            'matrix_vision' => $matrixVision,
            'simple_node_dump' => $simpleNodeDump,
        ]);
    }

    /**
     * @param Node[] $nodes
     */
    private function makeNodeClickable(array $nodes, string $uuid): string
    {
        $nodeTraverser = new NodeTraverser();
        $nodeTraverser->addVisitor(new NodeMarkerNodeVisitor());
        $nodeTraverser->traverse($nodes);

        $clickableAstPrinter = new ClickableAstPrinter($uuid);
        return $clickableAstPrinter->prettyPrint($nodes);
    }

    /**
     * @param Node[] $nodes
     * @return Node[]
     */
    private function focusNodes(?int $activeNodeId, array $nodes): array
    {
        // find selected node
        if ($activeNodeId) {
            $nodeFinder = new NodeFinder();
            $selectedNode = $nodeFinder->findFirst($nodes, static function (Node $node) use ($activeNodeId): bool {
                $nodeId = $node->getAttribute(AttributeKey::NODE_ID);
                return $activeNodeId === $nodeId;
            });

            if ($selectedNode) {
                $nodes = [$selectedNode];
            }
        }

        return $nodes;
    }
}
