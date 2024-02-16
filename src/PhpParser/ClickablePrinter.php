<?php

declare(strict_types=1);

namespace Rector\Website\PhpParser;

use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt;
use PhpParser\PrettyPrinter\Standard;
use Rector\Website\Enum\AttributeKey;

/**
 * @todo generate this automatically as alwasy the same contents
 */
final class ClickablePrinter extends Standard
{
    function __construct(private string $uuid)
    {
        parent::__construct();
    }

    protected function pStmt_Echo(Stmt\Echo_ $node): string
    {
        $nodeId = $node->getAttribute(AttributeKey::NODE_ID);

        return '<a href="/ast/' . $this->uuid . '/' . $nodeId . '">' . parent::pStmt_Echo($node) . '</a>';
    }

    protected function pScalar_String(String_ $node): string
    {
        $nodeId = $node->getAttribute(AttributeKey::NODE_ID);

        return '<a href="/ast/' . $this->uuid . '/' . $nodeId . '">' . parent::pScalar_String($node) . '</a>';
    }
}
