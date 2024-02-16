<?php

declare(strict_types=1);

namespace Rector\Website\PhpParser;

use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\PrettyPrinter\Standard;
use Rector\Website\Enum\AttributeKey;

/**
 * @todo generate this automatically as alwasy the same contents
 */
final class ClickablePrinter extends Standard
{
    public function __construct(
        private string $uuid
    ) {
        parent::__construct();
    }

    protected function pStmt_Echo(Echo_ $echo): string
    {
        $nodeId = $echo->getAttribute(AttributeKey::NODE_ID);

        return '<a href="/ast/' . $this->uuid . '/' . $nodeId . '">' . parent::pStmt_Echo($echo) . '</a>';
    }

    protected function pScalar_String(String_ $string): string
    {
        $nodeId = $string->getAttribute(AttributeKey::NODE_ID);

        return '<a href="/ast/' . $this->uuid . '/' . $nodeId . '">' . parent::pScalar_String($string) . '</a>';
    }
}
