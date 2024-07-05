<?php

declare(strict_types=1);

namespace App\RuleFilter;

use App\Exception\ShouldNotHappenException;
use App\PhpParser\SimplePhpParser;
use App\RuleFilter\Markdown\MarkdownDiffer;
use App\RuleFilter\PhpParser\NodeFactory\RectorConfigFactory;
use App\RuleFilter\PhpParser\Printer\RectorConfigStmtsPrinter;
use App\RuleFilter\ValueObject\ConfiguredDiffSample;
use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt;
use PhpParser\NodeFinder;
use Webmozart\Assert\Assert;

final readonly class ConfiguredDiffSamplesFactory
{
    public function __construct(
        private MarkdownDiffer $markdownDiffer,
        private SimplePhpParser $simplePhpParser,
        private RectorConfigFactory $rectorConfigFactory,
        private RectorConfigStmtsPrinter $rectorConfigStmtsPrinter,
    ) {
    }

    /**
     * @return ConfiguredDiffSample[]
     */
    public function createFromRectorRuleFilePath(string $ruleClass, string $filePath): array
    {
        Assert::fileExists($filePath);

        $stmts = $this->simplePhpParser->parseFile($filePath);

        $configuredCodeSampleNews = $this->configConfiguredCodeSampleNews($stmts);

        $configuredDiffSamples = [];

        foreach ($configuredCodeSampleNews as $configuredCodeSampleNew) {
            $diffSample = $this->resolveDiffSample($configuredCodeSampleNew);

            $thirdArg = $configuredCodeSampleNew->getArgs()[2];

            $stmts = $this->rectorConfigFactory->createConfigured($ruleClass, $thirdArg->value);
            $printedConfiguration = $this->rectorConfigStmtsPrinter->print($stmts);

            $configuredDiffSamples[] = new ConfiguredDiffSample($diffSample, $printedConfiguration);
        }

        return $configuredDiffSamples;
    }

    /**
     * @param Stmt[] $stmts
     * @return New_[]
     */
    private function configConfiguredCodeSampleNews(array $stmts): array
    {
        $nodeFinder = new NodeFinder();

        /** @var New_[] $configuredCodeSampleNews */
        $configuredCodeSampleNews = $nodeFinder->find($stmts, function (Node $node) {
            // we look for "new ConfiguredCodeSample()"
            return $this->isNewWithClassName(
                $node,
                'Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample'
            );
        });

        return $configuredCodeSampleNews;
    }

    private function isNewWithClassName(Node $node, string $desiredClassName): bool
    {
        if (! $node instanceof New_) {
            return false;
        }

        if (! $node->class instanceof Name) {
            return false;
        }

        return $node->class->toString() === $desiredClassName;
    }

    private function resolveDiffSample(New_ $new): string
    {
        $firstArg = $new->getArgs()[0];

        $codeBefore = $firstArg->value;
        if (! $codeBefore instanceof String_) {
            throw new ShouldNotHappenException();
        }

        $secondArg = $new->getArgs()[1];
        $codeAfter = $secondArg->value;
        if (! $codeAfter instanceof String_) {
            throw new ShouldNotHappenException();
        }

        return $this->markdownDiffer->diff($codeBefore->value, $codeAfter->value);
    }
}
