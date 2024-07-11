<?php

declare(strict_types=1);

namespace App\Tests\Validator\Rules\HasRectorRule\HasRectorRuleFixture\valid;

use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class SomeRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
    }

    public function getNodeTypes(): array
    {
        return [];
    }

    public function refactor(\PhpParser\Node $node)
    {
    }
}
