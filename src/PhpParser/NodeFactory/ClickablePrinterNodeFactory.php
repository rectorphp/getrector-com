<?php

declare(strict_types=1);

namespace App\PhpParser\NodeFactory;

use App\Enum\AttributeKey;
use App\Enum\ComponentEvent;
use PhpParser\BuilderFactory;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassMethod;

final class ClickablePrinterNodeFactory
{
    public function createGetAttributeAssign(Variable $paramVariable): Assign
    {
        $classConstFetch = new ClassConstFetch(new FullyQualified(AttributeKey::class), 'NODE_ID');

        $getAttributeMethodCall = new MethodCall(
            new Variable($paramVariable->name),
            'getAttribute',
            [new Arg($classConstFetch)]
        );

        return new Assign(new Variable('nodeId'), $getAttributeMethodCall);
    }

    public function createSprintfFuncCall(ClassMethod $originalClassMethod, Variable $paramVariable): FuncCall
    {
        $sprintfFuncCall = new FuncCall(new Name('sprintf'));

        // parent method call
        $staticCall = new StaticCall(
            new Name('parent'),
            $originalClassMethod->name->toString(),
            [new Arg($paramVariable)]
        );

        $builderFactory = new BuilderFactory();

        $activeNodeTernary = new Ternary(
            new Equal(new PropertyFetch(new Variable('this'), 'activeNodeId'), new Variable('nodeId')),
            new String_('class="active-node"'),
            new String_('')
        );

        $args = $builderFactory->args([
            '<a href="#" wire:click.prevent="$dispatch(\'' . ComponentEvent::SELECT_NODE . '\', {
            nodeId: %s,
        })" %s>%s</a>',
            new Variable('nodeId'),
            $activeNodeTernary,
            $staticCall,
        ]);

        $sprintfFuncCall->args = $args;

        return $sprintfFuncCall;
    }
}
