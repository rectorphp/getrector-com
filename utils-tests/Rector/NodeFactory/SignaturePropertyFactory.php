<?php

declare(strict_types=1);

namespace Rector\Website\Utils\Tests\Rector\NodeFactory;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use Rector\Core\Exception\NotImplementedYetException;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\NodeNameResolver\NodeNameResolver;

final class SignaturePropertyFactory
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ValueResolver $valueResolver,
    ) {
    }

    public function createFromConfigureClassMethod(ClassMethod $classMethod): Property
    {
        $commandName = $this->resolveCommandName($classMethod);
        if ($commandName === null) {
            throw new NotImplementedYetException();
        }

        $signatureValue = $commandName;

        $propertyProperty = new PropertyProperty('signature');
        $propertyProperty->default = new String_($signatureValue);

        return new Property(Class_::MODIFIER_PROTECTED, [$propertyProperty]);
    }

    private function resolveCommandName(ClassMethod $classMethod): ?string
    {
        foreach ((array) $classMethod->stmts as $classMethodStmt) {
            if (! $classMethodStmt instanceof Expression) {
                continue;
            }

            $expression = $classMethodStmt->expr;

            // resolve setName() method call arg value
            if (! $expression instanceof MethodCall) {
                continue;
            }

            $methodCall = $expression;
            if (! $this->nodeNameResolver->isName($methodCall->name, 'setName')) {
                continue;
            }

            $firstArgValue = $methodCall->getArgs()[0]
->value;
            return $this->valueResolver->getValue($firstArgValue);
        }

        return null;
    }
}
