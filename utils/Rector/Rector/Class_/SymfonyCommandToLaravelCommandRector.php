<?php

declare(strict_types=1);

namespace Rector\Website\Utils\Rector\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symfony\Component\Console\Command\Command;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Website\Utils\Tests\Rector\Rector\Class_\SymfonyCommandToLaravelCommandRectorTest
 */
final class SymfonyCommandToLaravelCommandRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Convert Symfony console Command to Laravel one',
            [
                // ...
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Class_
    {
        if (! $this->isObjectType($node, new ObjectType(Command::class))) {
            return null;
        }

        if ($node->getMethod('handle') instanceof ClassMethod) {
            return null;
        }

        $node->extends = new FullyQualified('Illuminate\Console\Command');

        $executeClassMethod = $node->getMethod('execute');

        // no execute
        if (! $executeClassMethod instanceof ClassMethod) {
            return $node;
        }

        // remove params
        $executeClassMethod->params = [];
        $executeClassMethod->name = new Identifier('handle');

        // update contents with option()/argument() calls

        $this->traverseNodesWithCallable((array) $executeClassMethod->stmts, function (Node $node): ?MethodCall {
            // @todo
            if (! $node instanceof MethodCall) {
                return null;
            }

            if ($this->isName($node->name, 'getArgument')) {
                $node->name = new Identifier('argument');
            }

            if ($this->isName($node->name, 'getOption')) {
                $node->name = new Identifier('option');
            }

            return $node;
        });

        return $node;
    }
}
