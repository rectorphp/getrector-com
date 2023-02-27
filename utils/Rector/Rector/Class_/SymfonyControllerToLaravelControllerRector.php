<?php

declare(strict_types=1);

namespace Rector\Website\Utils\Rector\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Website\Utils\Tests\Rector\Rector\Class_\SymfonyControllerToLaravelControllerRector\SymfonyControllerToLaravelControllerRectorTest
 */
final class SymfonyControllerToLaravelControllerRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Convert Symfony controller to Laravel one',
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
        if (! $this->isObjectType(
            $node,
            new ObjectType('Symfony\Bundle\FrameworkBundle\Controller\AbstractController')
        )) {
            return null;
        }

        $node->extends = new FullyQualified('Illuminate\Routing\Controller');

        foreach ($node->getMethods() as $classMethod) {
            if (! $classMethod->isPublic()) {
                continue;
            }

            // skip magic methods except __invoke()
            if ($classMethod->isMagic() && ! $this->isName($classMethod->name, '__invoke')) {
                continue;
            }

            $this->refactorActionClassMethod($classMethod);
        }

        // return response type
        // remove .twig suffix on templates

        return $node;
    }

    private function removeTwigSuffixFromTemplateNameArg(MethodCall $methodCall): void
    {
        $templateNameArg = $methodCall->getArgs()[0];
        if (! $templateNameArg->value instanceof String_) {
            return;
        }

        $templateNameString = $templateNameArg->value;
        if (! str_ends_with($templateNameString->value, '.twig')) {
            return;
        }

        // remove .twig suffix
        $templateNameString->value = substr($templateNameString->value, 0, -5);
    }

    private function refactorActionClassMethod(ClassMethod $classMethod): bool
    {
        // probably action method
        $hasRender = false;

        $this->traverseNodesWithCallable((array) $classMethod->stmts, function (Node $node) use (
            &$hasRender
        ): ?FuncCall {
            if (! $node instanceof MethodCall) {
                return null;
            }

            if (! $this->isName($node->name, 'render')) {
                return null;
            }

            $this->removeTwigSuffixFromTemplateNameArg($node);

            $hasRender = true;

            return new FuncCall(new FullyQualified('view'), $node->getArgs());
        });

        if ($hasRender) {
            // @todo maybe check contents for render() first
            $classMethod->returnType = new FullyQualified('Illuminate\Contracts\View\View');
        }

        return $hasRender;
    }
}
