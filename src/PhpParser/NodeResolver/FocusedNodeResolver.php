<?php

declare(strict_types=1);

namespace App\PhpParser\NodeResolver;

use App\Enum\AttributeKey;
use PhpParser\Node;
use PhpParser\NodeFinder;

final class FocusedNodeResolver
{
    /**
     * @param Node[] $nodes
     */
    public function focus(array $nodes, int $activeNodeId): ?Node
    {
        // find selected node
        $nodeFinder = new NodeFinder();

        return $nodeFinder->findFirst($nodes, static function (Node $node) use ($activeNodeId): bool {
            $nodeId = $node->getAttribute(AttributeKey::NODE_ID);
            return $activeNodeId === $nodeId;
        });
    }
}
