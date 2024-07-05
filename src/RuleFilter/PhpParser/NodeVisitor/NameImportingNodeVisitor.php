<?php

declare(strict_types=1);

namespace App\RuleFilter\PhpParser\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
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
     * @return Use_[]
     */
    public function getImportedUses(): array
    {
        $uses = [];

        foreach ($this->getNamesToImport() as $nameToImport) {
            $uses[] = new Use_([new UseUse(new Name($nameToImport))]);
        }

        return $uses;
    }
}
