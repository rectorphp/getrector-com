<?php

declare(strict_types=1);

namespace App\RuleFilter\PhpParser\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\NodeVisitorAbstract;

final class NameImportingNodeVisitor extends NodeVisitorAbstract
{
    /**
     * @var string[]
     */
    private array $namesToImport = [];

    public function enterNode(Node $node)
    {
        if ($node instanceof FullyQualified) {
            $this->namesToImport[] = $node->toString();
            return new Name($node->getLast());
        }

        return null;
    }

    /**
     * @return string[]
     */
    public function getNamesToImport(): array
    {
        $uniqueNamesToImport = array_unique($this->namesToImport);
        sort($uniqueNamesToImport);

        return $uniqueNamesToImport;
    }

    /**
     * @return Node\Stmt\Use_[]
     */
    public function getImportedUses(): array
    {
        $uses = [];

        foreach ($this->getNamesToImport() as $nameToImport) {
            $uses[] = new Node\Stmt\Use_([new Node\Stmt\UseUse(new Name($nameToImport))]);
        }

        return $uses;
    }
}
