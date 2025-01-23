<?php

declare(strict_types=1);

namespace App\Livewire;

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
use Rector\CustomRules\SimpleNodeDumper;

final class AstComponent extends Component
{
    #[Url]
    public ?int $nodeId = null;

    public AstRun $astRun;

    public string $inputFormContents;

    #[On(ComponentEvent::SELECT_NODE)]
    public function selectNode(?int $nodeId): void
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
        } else {
            $simpleNodeDump = SimpleNodeDumper::dump($nodes);
        }

        // to trigger event in component javascript
        $this->dispatch(ComponentEvent::NODE_SELECTED);

        return view('livewire.ast-component', [
            'matrixVision' => $this->makesNodeClickable($nodes, $this->nodeId),
            'simpleNodeDump' => $simpleNodeDump,
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
}
