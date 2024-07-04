<?php

declare(strict_types=1);

namespace App\RuleFilter\ValueObject;

use App\RuleFilter\Markdown\MarkdownDiffer;
use PhpParser\Node;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Contract\Rector\RectorInterface;
use ReflectionProperty;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;
use Symplify\RuleDocGenerator\Contract\CodeSampleInterface;
use Webmozart\Assert\Assert;

final class RuleMetadata
{
    private ?int $filterScore = null;

    /**
     * @param array<class-string<Node>> $nodeTypes
     * @param string[] $sets
     * @param CodeSampleInterface[] $codeSamples
     */
    public function __construct(
        private string $ruleClass,
        private string $description,
        private array $codeSamples,
        private array $nodeTypes,
        private array $sets
    ) {
        Assert::isAOf($ruleClass, RectorInterface::class);
        Assert::allIsAOf($nodeTypes, Node::class);
    }

    public function getRuleShortClass(): string
    {
        return \basename(\str_replace('\\', '/', $this->ruleClass));
    }

    public function getSlug(): string
    {
        // turn "SomeRector" to "some-rector"
        return str($this->getRuleShortClass())
            ->snake('-')
            ->toString();
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @api used in blade
     */
    public function getRectorClass(): string
    {
        return $this->ruleClass;
    }

    public function getDiffCodeSample(): string
    {
        $codeSample = $this->codeSamples[0] ?? null;
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

    public function isConfigurable(): bool
    {
        return is_a($this->ruleClass, ConfigurableRectorInterface::class, true);
    }

    /**
     * @return string[]
     */
    public function getSets(): array
    {
        return $this->sets;
    }

    public function isInSet(string $set): bool
    {
        return in_array($set, $this->sets, true);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return $this->nodeTypes;
    }

    public function changeFilterScore(int $filterScore): void
    {
        $this->filterScore = $filterScore;
    }

    public function getFilterScore(): ?int
    {
        return $this->filterScore;
    }
}
