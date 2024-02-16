<?php

declare(strict_types=1);

use PhpParser\Builder\Method;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PhpParser\PrettyPrinter\Standard;
use Rector\Website\Enum\AttributeKey;
use Rector\Website\PhpParser\SimplePhpParser;

require __DIR__ . '/../vendor/autoload.php';

// simple script to generate clickable AST printer
// use in https://getrector.com/ast

$classBuilder = new \PhpParser\Builder\Class_('ClickableAstPrinter');
$classBuilder->makeFinal();
$classBuilder->extend('PhpParser\PrettyPrinter\Standard');

final class ClickableAstPrinterBuilder
{
    private SimplePhpParser $simplePhpParser;

    private \PhpParser\BuilderFactory $builderFactory;

    public function __construct()
    {
        $this->simplePhpParser = new SimplePhpParser();
        $this->builderFactory = new \PhpParser\BuilderFactory();
    }

    public function buildClass(string $standardPrinterFilePath): Class_
    {
        $standardPrinterClass = $this->simplePhpParser->parseFileToClass($standardPrinterFilePath);

        $classBuilder = $this->createClassBuilder();

        foreach ($standardPrinterClass->getMethods() as $classMethod) {
            // handle only protected method, as printer ones
            if (! $this->isNodePrinterMethod($classMethod)) {
                continue;
            }

            $decoratedClassMethod = $this->createClassMethodWithLink($classMethod);
            $classBuilder->addStmt($decoratedClassMethod);
        }

        return $classBuilder->getNode();
    }

    private function createClassBuilder(): \PhpParser\Builder\Class_
    {
        $classBuilder = new \PhpParser\Builder\Class_('ClickableAstPrinter');
        $classBuilder->makeFinal();
        $classBuilder->extend('PhpParser\PrettyPrinter\Standard');

        return $classBuilder;
    }

    private function isNodePrinterMethod(ClassMethod $classMethod): bool
    {
        if (! $classMethod->isProtected()) {
            return false;
        }

        $methodName = $classMethod->name->toString();
        if (! str_starts_with($methodName, 'p')) {
            return false;
        }

        // must have exactly one param
        if (count($classMethod->params) !== 1) {
            return false;
        }

        return $this->hasNodeTypeParam($classMethod);
    }

    private function hasNodeTypeParam(ClassMethod $classMethod): bool
    {
        foreach ($classMethod->params as $param) {
            if (! $param->type instanceof Name) {
                continue;
            }

            $paramType = $param->type->toString();

            if (is_a($paramType, Node::class, true)) {
                return true;
            }
        }

        return false;
    }

    private function createClassMethodWithLink(ClassMethod $originalClassMethod): ClassMethod
    {
        $methodBuilder = new Method($originalClassMethod->name->toString());
        $methodBuilder->makeProtected();
        $methodBuilder->addParams($originalClassMethod->params);
        $methodBuilder->setReturnType('string');

        // add wrapped method body
        /** @var Node\Param $originalParam */
        $originalParam = $originalClassMethod->params[0];

        $originalParamVariable = $originalParam->var;
        Webmozart\Assert\Assert::isInstanceOf($originalParamVariable, Node\Expr\Variable::class);

        $getAttributeMethodCall = new MethodCall(
            new Variable($originalParamVariable->name),
            'getAttribute',
            [new Arg(new ClassConstFetch(new FullyQualified(AttributeKey::class), 'NODE_ID'))]
        );

        $assign = new Assign(new Variable('nodeId'), $getAttributeMethodCall);
        $methodBuilder->addStmt($assign);

        $sprintfFuncCall = new FuncCall(new Name('sprintf'));

        // parent method call
        $parentMethodCall = new StaticCall(
            new Name('parent'),
            $originalClassMethod->name->toString(),
            [new Arg($originalParamVariable)]
        );

        $args = $this->builderFactory->args([
            '<a href="/ast/%s/%s">%s</a>',
            new Node\Expr\PropertyFetch(new Variable('this'), 'uuid'),
            new Variable('nodeId'),
            $parentMethodCall,
        ]);

        $methodBuilder->addStmt(new Return_($sprintfFuncCall));

        $sprintfFuncCall->args = $args;

        return $methodBuilder->getNode();
    }
}

$clickableAstPrinterBuilder = new ClickableAstPrinterBuilder();
$clickableAstPrinterClass = $clickableAstPrinterBuilder->buildClass(
    __DIR__ . '/../vendor/nikic/php-parser/lib/PhpParser/PrettyPrinter/Standard.php'
);

$standardPrinter = new Standard();
$printedClass = $standardPrinter->prettyPrint([$clickableAstPrinterClass]);
dd($printedClass);
