<?php

declare(strict_types=1);

namespace App\RuleFilter\ValueObject;

use App\Exception\ShouldNotHappenException;
use App\RuleFilter\ConfiguredDiffSamplesFactory;
use App\RuleFilter\Markdown\MarkdownDiffer;
use App\RuleFilter\PhpParser\NodeFactory\RectorConfigFactory;
use App\RuleFilter\PhpParser\Printer\RectorConfigStmtsPrinter;
use PhpParser\Node;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Contract\Rector\RectorInterface;
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
        private array $sets,
        private string $rectorRuleFilePath
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

    public function getConfiguration(): string
    {
        if ($this->isConfigurable()) {
            throw new ShouldNotHappenException(
                'Configuration for whole rule is available only for non configurable rule'
            );
        }

        /** @var RectorConfigFactory $rectorConfigFactory */
        $rectorConfigFactory = app(RectorConfigFactory::class);
        $configStmts = $rectorConfigFactory->createNormal($this->ruleClass);

        /** @var RectorConfigStmtsPrinter $rectorConfigStmtsPrinter */
        $rectorConfigStmtsPrinter = app(RectorConfigStmtsPrinter::class);
        return $rectorConfigStmtsPrinter->print($configStmts);
    }

    /**
     * @return ConfiguredDiffSample[]
     */
    public function getConfiguredDiffSamples(): array
    {
        // nothing to return
        if (! $this->isConfigurable()) {
            return [];
        }

        /** @var ConfiguredDiffSamplesFactory $configuredDiffSamplesFactory */
        $configuredDiffSamplesFactory = app(ConfiguredDiffSamplesFactory::class);
        return $configuredDiffSamplesFactory->createFromRectorRuleFilePath($this->ruleClass, $this->rectorRuleFilePath);
    }

    public function getDiffCodeSample(): string
    {
        $codeSample = $this->codeSamples[0] ?? null;
        if (! $codeSample instanceof CodeSampleInterface) {
            return '';
        }

        /** @var MarkdownDiffer $markdownDiffer */
        $markdownDiffer = app(MarkdownDiffer::class);

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
