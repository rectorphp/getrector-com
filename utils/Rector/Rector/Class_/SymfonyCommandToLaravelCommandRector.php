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
use Rector\Website\Utils\Tests\Rector\NodeFactory\SignaturePropertyFactory;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Website\Utils\Tests\Rector\Rector\Class_\SymfonyCommandToLaravelCommandRector\SymfonyControllerToLaravelControllerRectorTest
 */
final class SymfonyCommandToLaravelCommandRector extends AbstractRector
{
    public function __construct(
        private readonly SignaturePropertyFactory $signaturePropertyFactory,
    ) {
    }

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
        if ($this->shouldSkip($node)) {
            return null;
        }

        $node->extends = new FullyQualified('Illuminate\Console\Command');

        $executeClassMethod = $node->getMethod('execute');

        // no execute
        if ($executeClassMethod instanceof ClassMethod) {
            // remove params
            $executeClassMethod->params = [];
            $executeClassMethod->name = new Identifier('handle');

            // update contents with option()/argument() calls
            $this->refactorGetOptionGetArgumentMethodCalls($executeClassMethod);
        }

        $configureClassMethod = $node->getMethod('configure');
        if ($configureClassMethod instanceof ClassMethod) {
            $property = $this->signaturePropertyFactory->createFromConfigureClassMethod($configureClassMethod);
            $node->stmts = array_merge([$property], $node->stmts);

            // remove configure() method
            $configureClassMethodPosition = array_search($configureClassMethod, $node->stmts, true);
            unset($node->stmts[$configureClassMethodPosition]);
        }

        return $node;
    }

    private function shouldSkip(Class_ $class): bool
    {
        if (! $this->isObjectType($class, new ObjectType('Symfony\Component\Console\Command\Command'))) {
            return true;
        }

        return $this->isObjectType($class, new ObjectType('Illuminate\Console\Command'));
    }

    private function refactorGetOptionGetArgumentMethodCalls(ClassMethod $executeClassMethod): void
    {
        $this->traverseNodesWithCallable((array) $executeClassMethod->stmts, function (Node $node): ?MethodCall {
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
    }
}
