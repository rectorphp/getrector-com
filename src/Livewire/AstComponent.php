<?php

declare(strict_types=1);

namespace Rector\Website\Livewire;

use PhpParser\Node\Stmt\UseUse;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Stmt;
use PhpParser\Node\Param;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\Expr\Variable;
use Rector\CustomRules\SimpleNodeDumper;
use Rector\Website\Entity\AstRun;
use Rector\Website\PhpParser\ClickablePrinter;
use Rector\Website\PhpParser\NodeResolver\FocusedNodeResolver;
use Rector\Website\PhpParser\SimplePhpParser;

final class AstComponent extends Component
{
    #[Url]
    public ?int $nodeId = null;

    private AstRun $astRun;

    public function mount(AstRun $astRun): void
    {
        $this->astRun = $astRun;
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

        return view('livewire.ast-component', [
            'matrixVision' => $this->makeNodeClickable($nodes, (string) $this->astRun->getUuid(), $this->nodeId),
            'simpleNodeDump' => $simpleNodeDump,
            'targetNodeClass' => $targetNodeClass,
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
        if ($node instanceof UseUse || $node instanceof AttributeGroup) {
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
