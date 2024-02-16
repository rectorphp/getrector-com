<?php

declare(strict_types=1);

use PhpParser\Builder\Method;
use PhpParser\BuilderFactory;
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
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\PrettyPrinter\Standard;
use Rector\ValueObject\MethodName;
use Rector\Website\Enum\AttributeKey;
use Rector\Website\PhpParser\SimplePhpParser;
use Webmozart\Assert\Assert;

require __DIR__ . '/../vendor/autoload.php';

// simple script to generate clickable AST printer
// use in https://getrector.com/ast

final class ClickableAstPrinterBuilder
{
    private SimplePhpParser $simplePhpParser;

    private BuilderFactory $builderFactory;

    public function __construct()
    {
        $this->simplePhpParser = new SimplePhpParser();
        $this->builderFactory = new BuilderFactory();
    }

    public function buildClass(string $standardPrinterFilePath): Class_
    {
        $standardPrinterClass = $this->simplePhpParser->parseFileToClass($standardPrinterFilePath);

        $constructClassMethod = $this->createConstructClassMethod();

        $classBuilder = $this->createClassBuilder()
            ->addStmt($constructClassMethod);

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
        $classBuilder->extend(new FullyQualified('PhpParser\PrettyPrinter\Standard'));

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
        $methodBuilder = $this->createMethodBuilder($originalClassMethod);

        // add wrapped method body
        /** @var Node\Param $originalParam */
        $originalParam = $originalClassMethod->params[0];

        $originalParamVariable = $originalParam->var;
        Assert::isInstanceOf($originalParamVariable, Variable::class);

        $assign = $this->createAttributeAssign($originalParamVariable);
        $sprintfFuncCall = $this->createSprintfFuncCall($originalClassMethod, $originalParamVariable);

        $methodBuilder->addStmts([$assign, new Return_($sprintfFuncCall)]);

        return $methodBuilder->getNode();
    }

    private function createMethodBuilder(ClassMethod $originalClassMethod): Method
    {
        $methodBuilder = new Method($originalClassMethod->name->toString());
        $methodBuilder->makeProtected();
        $methodBuilder->addParams($originalClassMethod->params);
        $methodBuilder->setReturnType('string');

        return $methodBuilder;
    }

    private function createSprintfFuncCall(ClassMethod $originalClassMethod, Variable $originalParamVariable): FuncCall
    {
        $sprintfFuncCall = new FuncCall(new Name('sprintf'));

        // parent method call
        $parentMethodCall = new StaticCall(
            new Name('parent'),
            $originalClassMethod->name->toString(),
            [new Arg($originalParamVariable)]
        );

        // string concat
        $args = $this->builderFactory->args([
            '<a href="/ast/%s/%s">%s</a>',
            new Node\Expr\PropertyFetch(new Variable('this'), 'uuid'),
            new Variable('nodeId'),
            $parentMethodCall,
        ]);

        $sprintfFuncCall->args = $args;

        return $sprintfFuncCall;
    }

    private function createAttributeAssign(Variable $paramVariable): Assign
    {
        $classConstFetch = new ClassConstFetch(new FullyQualified(AttributeKey::class), 'NODE_ID');

        $getAttributeMethodCall = new MethodCall(
            new Variable($paramVariable->name),
            'getAttribute',
            [new Arg($classConstFetch)]
        );

        return new Assign(new Variable('nodeId'), $getAttributeMethodCall);
    }

    private function createConstructClassMethod(): ClassMethod
    {
        $uuidParamBuilder = $this->builderFactory->param('uuid');
        $uuidParamBuilder->makePrivate();
        $uuidParamBuilder->setType('string');

        $parentStaticCall = new StaticCall(new Name('parent'), MethodName::CONSTRUCT);

        return $this->builderFactory->method(MethodName::CONSTRUCT)
            ->makePublic()
            ->addParam($uuidParamBuilder->getNode())
            ->addStmts([$parentStaticCall])
            ->getNode();
    }
}

$clickableAstPrinterBuilder = new ClickableAstPrinterBuilder();
$clickableAstPrinterClass = $clickableAstPrinterBuilder->buildClass(
    __DIR__ . '/../vendor/nikic/php-parser/lib/PhpParser/PrettyPrinter/Standard.php'
);

$localNamespace = new Namespace_(new Name('Rector\Website\PhpParser'));
$localNamespace->stmts[] = $clickableAstPrinterClass;

$standardPrinter = new Standard();
$printedClass = $standardPrinter->prettyPrintFile([$localNamespace]);

\Nette\Utils\FileSystem::write(getcwd() . '/src/PhpParser/ClickableAstPrinter.php', $printedClass);

echo sprintf('Done and generated to%ssrc/PhpParser/ClickableAstPrinter.php', PHP_EOL) . PHP_EOL;
