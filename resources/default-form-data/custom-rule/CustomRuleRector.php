<?php

declare(strict_types=1);

use PhpParser\Node;
use PhpParser\Node\Expr\Cast\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class CustomRuleRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [String_::class];
    }

    /**
     * @param String_ $node
     */
    public function refactor(Node $node)
    {
        // change to number :)
    }

    public function getRuleDefinition(): RuleDefinition
    {
    }
}
