<?php

declare(strict_types=1);

namespace App\Livewire;

use PhpParser\Node\UseItem;
use App\Ast\Entity\AstRun;
use App\Ast\PhpParser\ClickablePrinter;
use App\Enum\ComponentEvent;
use App\PhpParser\NodeResolver\FocusedNodeResolver;
use App\PhpParser\SimplePhpParser;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use Rector\CustomRules\SimpleNodeDumper;

final class AstComponent extends Component
{
    #[Url]
    public ?int $nodeId = null;

    public AstRun $astRun;

    #[On(ComponentEvent::SELECT_NODE)]
    public function selectNode(int $nodeId): void
    {
        $this->nodeId = $nodeId;
    }

    public function render(): View
    {
        /** @var SimplePhpParser $simplePhpParser */
        $simplePhpParser = app(SimplePhpParser::class);

        /** @var FocusedNodeResolver $focusedNodeResolver */
        $focusedNodeResolver = app(FocusedNodeResolver::class);

        $nodes = $simplePhpParser->parseString($this->astRun->getContent());

        $focusedNode = is_int($this->nodeId) && $this->nodeId > 0
            ? $focusedNodeResolver->focus($nodes, $this->nodeId)
            : null;

        if ($focusedNode instanceof Node) {
            $simpleNodeDump = SimpleNodeDumper::dump($focusedNode);
            $targetNodeClass = $this->resolveTargetNodeClass($focusedNode);
        } else {
            $simpleNodeDump = SimpleNodeDumper::dump($nodes);
            $targetNodeClass = null;
        }

        // to trigger event in component javascript
        $this->dispatch(ComponentEvent::NODE_SELECTED);

        return view('livewire.ast-component', [
            'matrixVision' => $this->makesNodeClickable($nodes, $this->nodeId),
            'simpleNodeDump' => $simpleNodeDump,
            'targetNodeClass' => $targetNodeClass,
        ]);
    }

    /**
     * @param Node[] $nodes
     */
    private function makesNodeClickable(array $nodes, ?int $activeNodeId): string
    {
        $clickablePrinter = new ClickablePrinter($activeNodeId);
        return $clickablePrinter->prettyPrint($nodes);
    }

    private function resolveTargetNodeClass(Node $node): string
    {
        if ($node instanceof UseItem || $node instanceof AttributeGroup) {
            $parentNode = $node->getAttribute('parent');
            return $parentNode::class;
        }

        if ($node instanceof Attribute) {
            $attributeGroup = $node->getAttribute('parent');
            $stmt = $attributeGroup->getAttribute('parent');
            return $stmt::class;
        }

        if ($node instanceof Stmt) {
            return $node::class;
        }

        if ($node instanceof Variable) {
            $parentNode = $node->getAttribute('parent');

            // special case
            if ($parentNode instanceof Param) {
                return $parentNode::class;
            }
        }

        // target one level up
        if ($node instanceof Identifier || $node instanceof Name || $node instanceof Variable) {
            $parentNode = $node->getAttribute('parent');
            return $this->resolveTargetNodeClass($parentNode);
        }

        return $node::class;
    }
}
