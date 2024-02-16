<?php

declare(strict_types=1);

namespace Rector\Website\PhpParser\NodeVisior;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use Rector\Website\Enum\AttributeKey;

final class NodeMarkerNodeVisitor extends NodeVisitorAbstract
{
    private int $counter = 1;

    public function enterNode(Node $node): ?Node
    {
        $node->setAttribute(AttributeKey::NODE_ID, $this->counter);
        ++$this->counter;

        return null;
    }
}
