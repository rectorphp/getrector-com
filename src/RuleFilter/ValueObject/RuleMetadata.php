<?php

declare(strict_types=1);

namespace App\RuleFilter\ValueObject;

use App\Enum\FindRule\GroupName;
use App\Exception\ShouldNotHappenException;
use App\RuleFilter\ConfiguredDiffSamplesFactory;
use App\RuleFilter\Markdown\MarkdownDiffer;
use App\RuleFilter\PhpParser\NodeFactory\RectorConfigFactory;
use App\RuleFilter\PhpParser\Printer\RectorConfigStmtsPrinter;
use Nette\Utils\Strings;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Contract\Rector\RectorInterface;
use Symplify\RuleDocGenerator\Contract\CodeSampleInterface;
use Webmozart\Assert\Assert;

final class RuleMetadata
{
    private ?int $filterScore = null;

    /**
     * @param RectorSet[] $sets
     * @param CodeSampleInterface[] $codeSamples
     */
    public function __construct(
        private readonly string $ruleClass,
        private readonly string $description,
        private array $codeSamples,
        private readonly array $sets,
        private readonly string $rectorRuleFilePath
    ) {
        Assert::isAOf($ruleClass, RectorInterface::class);
        Assert::allIsAOf($sets, RectorSet::class);
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
        // change `some` to <code>some</code>
        return Strings::replace($this->description, '#`(.*?)`#', '<code>$1</code>');
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
     * @return CodeSampleInterface[]
     */
    public function getCodeSamples(): array
    {
        return $this->codeSamples;
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
     * @return RectorSet[]
     */
    public function getSets(): array
    {
        return $this->sets;
    }

    public function isPartOfSets(): bool
    {
        return $this->sets !== [];
    }

    public function changeFilterScore(int $filterScore): void
    {
        $this->filterScore = $filterScore;
    }

    public function getFilterScore(): ?int
    {
        return $this->filterScore;
    }

    public function belongToSetGroup(string $setGroup): bool
    {
        if ($this->isCommunityRule()) {
            return $setGroup === GroupName::LARAVEL;
        }

        foreach ($this->sets as $set) {
            if ($set->getGroupName() === $setGroup) {
                return true;
            }
        }

        return false;
    }

    private function isCommunityRule(): bool
    {
        return str_starts_with($this->ruleClass, 'RectorLaravel');
    }
}
