<?php

declare(strict_types=1);

namespace Rector\Website\ValueObject;

use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RichRuleDefinition
{
    /**
     * @param string[] $sets
     */
    public function __construct(
        private RuleDefinition $ruleDefinition,
        private array $sets,
        private int $rank
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return $this->ruleDefinition;
    }

    public function getRank(): int
    {
        return $this->rank;
    }
}
