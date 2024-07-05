<?php

declare(strict_types=1);

namespace App\RuleFilter;

use App\Exception\ShouldNotHappenException;
use App\PhpParser\SimplePhpParser;
use App\RuleFilter\Markdown\MarkdownDiffer;
use App\RuleFilter\ValueObject\ConfiguredDiffSample;
use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt;
use PhpParser\NodeFinder;
use PhpParser\PrettyPrinter\Standard;
use Webmozart\Assert\Assert;

final readonly class ConfiguredDiffSamplesFactory
{
    public function __construct(
        private MarkdownDiffer $markdownDiffer,
        private SimplePhpParser $simplePhpParser,
    ) {
    }

    /**
     * @return ConfiguredDiffSample[]
     */
    public function createFromRectorRuleFilePath(string $filePath): array
    {
        Assert::fileExists($filePath);

        $stmts = $this->simplePhpParser->parseFile($filePath);

        $configuredCodeSampleNews = $this->configConfiguredCodeSampleNews($stmts);

        $configuredDiffSamples = [];

        foreach ($configuredCodeSampleNews as $configuredCodeSampleNew) {
            $before = $configuredCodeSampleNew->getArgs()[0]
->value;
            if (! $before instanceof String_) {
                throw new ShouldNotHappenException();
            }

            $after = $configuredCodeSampleNew->getArgs()[1]
->value;
            if (! $after instanceof String_) {
                throw new ShouldNotHappenException();
            }

            $configuration = $configuredCodeSampleNew->getArgs()[2]
->value;
            $diffSample = $this->markdownDiffer->diff($before->value, $after->value);

            // include full configuration here, not just with placeholder
            // @todo print smartly with impors and newlines or something :)
            $standard = new Standard();
            $printedConfiguration = $standard->prettyPrintExpr($configuration);

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
}
