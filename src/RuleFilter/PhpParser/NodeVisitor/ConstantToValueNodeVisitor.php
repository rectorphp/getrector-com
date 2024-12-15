<?php

declare(strict_types=1);

namespace App\RuleFilter\PhpParser\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeVisitorAbstract;

final class ConstantToValueNodeVisitor extends NodeVisitorAbstract
{
    public function __construct(
        private readonly string $ruleClass
    ) {
    }

    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof ClassConstFetch) {
            return null;
        }

        if (! $node->class instanceof Name) {
            return null;
        }

        if ($node->class->toString() !== 'self') {
            return null;
        }

        // replace with value
        if (! $node->name instanceof Identifier) {
            return null;
        }

        $constantReference = $this->ruleClass . '::' . $node->name->toString();
        $constantValue = constant($constantReference);

        return new String_($constantValue);
    }
}
