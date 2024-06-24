<?php

declare(strict_types=1);

namespace App\PhpParser\NodeVisior;

use App\Enum\AttributeKey;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

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
