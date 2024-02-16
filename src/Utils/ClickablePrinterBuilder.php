<?php

declare(strict_types=1);

namespace Rector\Website\Utils;

use PhpParser\Builder\Method;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\DeclareDeclare;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\PrettyPrinter\Standard;
use Rector\ValueObject\MethodName;
use Rector\Website\PhpParser\NodeFactory\ClickablePrinterNodeFactory;
use Rector\Website\PhpParser\SimplePhpParser;
use Webmozart\Assert\Assert;

/**
 * Builds
 * @see \Rector\Website\PhpParser\ClickablePrinter
 */
final class ClickablePrinterBuilder
{
    /**
     * @var string
     */
    private const CLASS_NAME = 'ClickablePrinter';

    public function __construct(
        private readonly SimplePhpParser $simplePhpParser,
        private readonly BuilderFactory $builderFactory,
        private readonly ClickablePrinterNodeFactory $clickablePrinterNodeFactory
    ) {
    }

    /**
     * @return Stmt[]
     */
    public function buildFileStmts(Class_ $class): array
    {
        $strictTypesDeclare = new Declare_([new DeclareDeclare('strict_types', new LNumber(1))]);

        $namespace = new Namespace_(new Name('Rector\Website\PhpParser'));
        $namespace->stmts[] = $class;

        return [$strictTypesDeclare, $namespace];
    }

    public function buildClass(string $standardPrinterFilePath): Class_
    {
        $standardPrinterClass = $this->simplePhpParser->parseFileToClass($standardPrinterFilePath);

        $constructClassMethod = $this->createConstructClassMethod();

        $classBuilder = $this->createClassBuilder()
            ->addStmt($constructClassMethod);

        foreach ($standardPrinterClass->getMethods() as $classMethod) {
            // handle only protected method, as printer ones
            if (! $this->isUsefulNodePrinterMethod($classMethod)) {
                continue;
            }

            $decoratedClassMethod = $this->createClassMethodWithLink($classMethod);
            $classBuilder->addStmt($decoratedClassMethod);
        }

        return $classBuilder->getNode();
    }

    private function createClassBuilder(): \PhpParser\Builder\Class_
    {
        $class = new \PhpParser\Builder\Class_(self::CLASS_NAME);
        $class->makeFinal();
        $class->extend(new FullyQualified(Standard::class));

        return $class;
    }

    private function isUsefulNodePrinterMethod(ClassMethod $classMethod): bool
    {
        if (! $classMethod->isProtected()) {
            return false;
        }

        $methodName = $classMethod->name->toString();
        if (! str_starts_with($methodName, 'p')) {
            return false;
        }

        // useful methods
        if ($methodName === 'pObjectProperty') {
            return true;
        }

        // skip overly detailed method, that take context off
        if ($methodName === 'pStaticDereferenceLhs') {
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

        $assign = $this->clickablePrinterNodeFactory->createGetAttributeAssign($originalParamVariable);
        $sprintfFuncCall = $this->clickablePrinterNodeFactory->createSprintfFuncCall(
            $originalClassMethod,
            $originalParamVariable
        );

        $methodBuilder->addStmts([$assign, new Return_($sprintfFuncCall)]);

        return $methodBuilder->getNode();
    }

    private function createMethodBuilder(ClassMethod $originalClassMethod): Method
    {
        $method = new Method($originalClassMethod->name->toString());
        $method->makeProtected();
        $method->addParams($originalClassMethod->params);
        $method->setReturnType('string');

        return $method;
    }

    private function createConstructClassMethod(): ClassMethod
    {
        $uuidParam = $this->builderFactory->param('uuid')
            ->makePrivate()
            ->setType('string')
            ->getNode();

        $activeNodeIdParam = $this->builderFactory->param('activeNodeId')
            ->makePrivate()
            ->setType('?int')
            ->getNode();

        $parentStaticCall = new StaticCall(new Name('parent'), MethodName::CONSTRUCT);

        return $this->builderFactory->method(MethodName::CONSTRUCT)
            ->makePublic()
            ->addParams([$uuidParam, $activeNodeIdParam])
            ->addStmts([$parentStaticCall])
            ->getNode();
    }
}
