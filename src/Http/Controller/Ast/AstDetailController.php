<?php

declare(strict_types=1);

namespace Rector\Website\Http\Controller\Ast;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use PhpParser\Node;
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
use PhpParser\NodeFinder;
>>>>>>> a13a193 (working prorotoye)
use PhpParser\NodeTraverser;
use Rector\CustomRules\SimpleNodeDumper;
use Rector\Website\Entity\AstRun;
use Rector\Website\Enum\AttributeKey;
use Rector\Website\PhpParser\ClickablePrinter;
use Rector\Website\PhpParser\NodeVisior\NodeMarkerNodeVisitor;
use Rector\Website\PhpParser\SimplePhpParser;
use Rector\Website\Repository\AstRunRepository;
=======
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\InlineHTML;
=======
>>>>>>> 08380db (misc)
use PhpParser\NodeTraverser;
use Rector\CustomRules\SimpleNodeDumper;
use Rector\Website\Entity\AstRun;
use Rector\Website\PhpParser\ClickablePrinter;
use Rector\Website\PhpParser\NodeVisior\InlineHtmlNodeVisitor;
use Rector\Website\PhpParser\NodeVisior\NodeMarkerNodeVisitor;
use Rector\Website\PhpParser\SimplePhpParser;
use Rector\Website\Repository\AstRunRepository;
<<<<<<< HEAD
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
>>>>>>> aea392f (misc)
=======
>>>>>>> 08380db (misc)

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

<<<<<<< HEAD
<<<<<<< HEAD
        // @todo fill the node that is being clicked, e.g. based on the node hash
        // + colorize
        $matrixVision = $this->makeNodeClickable($nodes, $uuid);

        // find selected node
        if ($activeNodeId) {
            $nodeFinder = new NodeFinder();
            $selectedNode = $nodeFinder->findFirst($nodes, function (\PhpParser\Node $node) use ($activeNodeId) {
                    $nodeId = $node->getAttribute(AttributeKey::NODE_ID);
                    return $activeNodeId === $nodeId;
            });

            if ($selectedNode) {
                $nodes = [$selectedNode];
            }
        }

        $simpleNodeDump = SimpleNodeDumper::dump($nodes);

        return \view('ast/ast_detail', [
            'page_title' => 'Play with AST',
            'matrix_vision' => $matrixVision,
=======
        // colorize :)
=======
>>>>>>> 120bcef (misc)
        // @todo fill the node that is being clicked, e.g. based on the node hash
        // + colorize
        $matrixVision = $this->makeNodeClickable($nodes);

        $simpleNodeDump = SimpleNodeDumper::dump($nodes);

        return \view('ast/ast_detail', [
            'page_title' => 'Play with AST',
<<<<<<< HEAD
            'ast_run' => $astRun,
            'matrix_vision' => $this->makeNodeClickable($nodes),
>>>>>>> aea392f (misc)
=======
            'matrix_vision' => $matrixVision,
>>>>>>> 120bcef (misc)
            'simple_node_dump' => $simpleNodeDump,
        ]);
    }

    /**
     * @param Node[] $nodes
<<<<<<< HEAD
<<<<<<< HEAD
     */
    private function makeNodeClickable(array $nodes, string $uuid): string
    {
=======
     * @return Node[]
     */
    private function makeNodeClickable(array $nodes): string
    {
        // or custom printer?

>>>>>>> aea392f (misc)
=======
     */
    private function makeNodeClickable(array $nodes): string
    {
>>>>>>> 120bcef (misc)
        $nodeTraverser = new NodeTraverser();
        $nodeTraverser->addVisitor(new NodeMarkerNodeVisitor());
        $nodeTraverser->traverse($nodes);

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
        $standard = new ClickablePrinter();
=======
        $standard = new ClickablePrinter($uuid);
>>>>>>> a13a193 (working prorotoye)
        return $standard->prettyPrint($nodes);
=======
//        $nodeTraverser = new NodeTraverser();
//        $nodeTraverser->addVisitor(new InlineHtmlNodeVisitor());
//        $nodeTraverser->traverse($nodes);

=======
        //        $nodeTraverser = new NodeTraverser();
        //        $nodeTraverser->addVisitor(new InlineHtmlNodeVisitor());
        //        $nodeTraverser->traverse($nodes);
>>>>>>> 08380db (misc)

        $standard = new ClickablePrinter();
        return $standard->prettyPrint($nodes);

        die;
>>>>>>> aea392f (misc)
=======
        $standard = new ClickablePrinter();
        return $standard->prettyPrint($nodes);
>>>>>>> 120bcef (misc)
    }
}
