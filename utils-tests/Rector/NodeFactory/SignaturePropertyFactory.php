<?php

declare(strict_types=1);

namespace Rector\Website\Utils\Tests\Rector\NodeFactory;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use Rector\Core\Exception\NotImplementedYetException;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\NodeNameResolver\NodeNameResolver;

final class SignaturePropertyFactory
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ValueResolver $valueResolver,
        private readonly BetterNodeFinder $betterNodeFinder,
    ) {
    }

    public function createFromConfigureClassMethod(ClassMethod $classMethod): Property
    {
        $commandName = $this->resolveCommandName($classMethod);
        if ($commandName === null) {
            throw new NotImplementedYetException();
        }

        $commandArguments = $this->resolveCommandArguments($classMethod);

        $signatureValue = $commandName;
        if ($commandArguments) {
            $signatureValue .= ' {' . $commandArguments . '}';
        }

        $commandOptions = $this->resolveCommandOptions($classMethod);
        if ($commandOptions) {
            $signatureValue .= ' ' . $commandOptions;
        }

        $propertyProperty = new PropertyProperty('signature');
        $propertyProperty->default = new String_($signatureValue);

        return new Property(Class_::MODIFIER_PROTECTED, [$propertyProperty]);
    }

    private function resolveCommandName(ClassMethod $classMethod): ?string
    {
        /** @var MethodCall[] $methodCalls */
        $methodCalls = $this->betterNodeFinder->findInstanceOf($classMethod, MethodCall::class);

        foreach ($methodCalls as $methodCall) {
            if (! $this->nodeNameResolver->isName($methodCall->name, 'setName')) {
                continue;
            }

            $firstArg = $methodCall->getArgs()[0];
            return $this->valueResolver->getValue($firstArg->value);
        }

        return null;
    }

    private function resolveCommandArguments(ClassMethod $classMethod): ?string
    {
        /** @var MethodCall[] $methodCalls */
        $methodCalls = $this->betterNodeFinder->findInstanceOf($classMethod, MethodCall::class);

        foreach ($methodCalls as $methodCall) {
            if (! $this->nodeNameResolver->isName($methodCall->name, 'addArgument')) {
                continue;
            }

            $firstArg = $methodCall->getArgs()[0];
            $argName = $this->valueResolver->getValue($firstArg->value);

            // @todo handle optinal/array/value options

            return $argName;
        }

        return null;
    }

    private function resolveCommandOptions(ClassMethod $classMethod): ?string
    {
        /** @var MethodCall[] $methodCalls */
        $methodCalls = $this->betterNodeFinder->findInstanceOf($classMethod, MethodCall::class);

        $optionNames = [];

        foreach ($methodCalls as $methodCall) {
            if (! $this->nodeNameResolver->isName($methodCall->name, 'addOption')) {
                continue;
            }

            $firstArg = $methodCall->getArgs()[0];
            $optionName = $this->valueResolver->getValue($firstArg->value);

            // @todo handle optinal/array/value options
            $optionNames[] = $optionName;
        }

        if ($optionNames === []) {
            return null;
        }

        $optionsValue = '';
        foreach ($optionNames as $key => $optionName) {
            $optionsValue .= '{' . $optionName . '}';

            // is last array item
            if ($key !== count($optionNames) - 1) {
                $optionsValue .= ' ';
            }
        }

        return $optionsValue;
    }
}
