<?php

declare(strict_types=1);

use PhpParser\Node;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;
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
        // change to number
        return new Int_(100);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('...', []);
    }
}
