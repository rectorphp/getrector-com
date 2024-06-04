<?php

declare(strict_types=1);

namespace Rector\Website\RuleFilter\ValueObject;

use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Website\RuleFilter\Markdown\MarkdownDiffer;
use ReflectionProperty;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;
use Symplify\RuleDocGenerator\Contract\CodeSampleInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RuleMetadata
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

    public function getDiffCodeSample(): string
    {
        $codeSample = $this->ruleDefinition->getCodeSamples()[0] ?? null;
        if (! $codeSample instanceof CodeSampleInterface) {
            return '';
        }

        // this is required to show full diffs from start to end
        $unifiedDiffOutputBuilder = new UnifiedDiffOutputBuilder('');
        $contextLinesReflectionProperty = new ReflectionProperty($unifiedDiffOutputBuilder, 'contextLines');
        $contextLinesReflectionProperty->setValue($unifiedDiffOutputBuilder, 10000);

        $markdownDiffer = new MarkdownDiffer(new Differ($unifiedDiffOutputBuilder));

        return $markdownDiffer->diff($codeSample->getBadCode(), $codeSample->getGoodCode());
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
