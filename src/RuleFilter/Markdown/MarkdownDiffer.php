<?php

declare(strict_types=1);

namespace App\RuleFilter\Markdown;

use Nette\Utils\Strings;
use SebastianBergmann\Diff\Differ;

/**
 * @see https://github.com/symplify/rule-doc-generator/blob/main/src/Printer/CodeSamplePrinter/DiffCodeSamplePrinter.php
 *
 * @see \App\Tests\RuleFilter\Markdown\MarkdownDifferTest
 */
final class MarkdownDiffer
{
    /**
     * @var string
     * @see https://regex101.com/r/LE9Xwo/1
     */
    private const METADATA_REGEX = '#^(.*\n){1}#';

    /**
     * @var string
     * @see https://regex101.com/r/yf7u2L/1
     */
    private const SPACE_AND_NEWLINE_REGEX = '#( ){1,}\n#';

    public function __construct(
        private readonly Differ $differ,
    ) {
    }

    public function diff(string $old, string $new): string
    {
        if ($old === $new) {
            return '';
        }

        $diff = $this->differ->diff($old, $new);

        $diff = $this->clearUnifiedDiffOutputFirstLine($diff);
        return $this->removeTrailingWhitespaces($diff);
    }

    /**
     * Removes UnifiedDiffOutputBuilder generated pre-spaces " \n" => "\n"
     */
    private function removeTrailingWhitespaces(string $diff): string
    {
        $diff = Strings::replace($diff, self::SPACE_AND_NEWLINE_REGEX, PHP_EOL);

        return rtrim($diff);
    }

    private function clearUnifiedDiffOutputFirstLine(string $diff): string
    {
        return Strings::replace($diff, self::METADATA_REGEX, '');
    }
}
