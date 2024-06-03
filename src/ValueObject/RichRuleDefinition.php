<?php

declare(strict_types=1);

namespace Rector\Website\ValueObject;

use Rector\Contract\Rector\ConfigurableRectorInterface;
use Symplify\RuleDocGenerator\Contract\CodeSampleInterface;
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

    public function getRuleShortClass(): string
    {
        return $this->ruleDefinition->getRuleShortClass();
    }

    public function getRuleClass(): string
    {
        return $this->ruleDefinition->getRuleClass();
    }

    /**
     * @return CodeSampleInterface[]
     */
    public function getCodeSamples(): array
    {
        return $this->ruleDefinition->getCodeSamples();
    }

    public function getRank(): int
    {
        return $this->rank;
    }

    public function isConfigurable(): bool
    {
        return is_a($this->ruleDefinition->getRuleClass(), ConfigurableRectorInterface::class, true);
    }

    /**
     * @return string[]
     */
    public function getSets(): array
    {
        return $this->sets;
    }
}
