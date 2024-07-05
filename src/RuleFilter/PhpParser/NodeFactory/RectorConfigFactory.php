<?php

declare(strict_types=1);

namespace App\RuleFilter\PhpParser\NodeFactory;

use App\RuleFilter\PhpParser\NodeVisitor\NameImportingNodeVisitor;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeTraverser;

/**
 * @see \App\Tests\RuleFilter\PhpParser\RectorConfigStmtsPrinterTest
 */
final class RectorConfigFactory
{
    /**
     * @return Stmt[]
     */
    public function createConfigured(string $ruleClass, Expr $configurationExpr): array
    {
        $args = [new Arg($this->createRuleClassConstFetch($ruleClass)), new Arg($configurationExpr)];

        $withConfiguredMethodCall = new MethodCall($this->createConfigureStaticCall(), 'withConfiguredRule', $args);
        $return = new Return_($withConfiguredMethodCall);

        return $this->importFullyQualifiedToUses($return);
    }

    /**
     * @return Stmt[]
     */
    public function createNormal(string $ruleClass): array
    {
        $args = [new Arg(new Array_([new ArrayItem($this->createRuleClassConstFetch($ruleClass))]))];

        $withConfiguredMethodCall = new MethodCall($this->createConfigureStaticCall(), 'withRules', $args);

        $return = new Return_($withConfiguredMethodCall);
        return $this->importFullyQualifiedToUses($return);
    }

    /**
     * @return Stmt[]
     */
    private function importFullyQualifiedToUses(Return_ $return): array
    {
        $nodeTraverser = new NodeTraverser();

        $nameImportingNodeVisitor = new NameImportingNodeVisitor();
        $nodeTraverser->addVisitor($nameImportingNodeVisitor);
        $nodeTraverser->traverse([$return]);

        $importedUses = $nameImportingNodeVisitor->getImportedUses();

        return array_merge($importedUses, [new Nop(), $return]);
    }

    private function createConfigureStaticCall(): StaticCall
    {
        return new StaticCall(new FullyQualified('Rector\Config\RectorConfig'), 'configure');
    }

    private function createRuleClassConstFetch(string $ruleClass): ClassConstFetch
    {
        $fullyQualified = new FullyQualified($ruleClass);
        return new ClassConstFetch($fullyQualified, 'class');
    }
}
