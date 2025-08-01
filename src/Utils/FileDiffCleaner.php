<?php

declare(strict_types=1);

namespace App\Utils;

use App\Enum\Comment;
use ArrayLookup\AtLeast;
use Nette\Utils\Strings;

/**
 * @see \App\Tests\Utils\FileDiffCleanerTest
 */
final class FileDiffCleaner
{
    /**
     * @see https://regex101.com/r/59jq4J/1
     */
    private const string DIFF_HEADER_ORIGINAL_REGEX = '#--- Original\n#s';

    /**
     * @see https://regex101.com/r/QMXRXr/1
     */
    private const string DIFF_HEADER_NEW_REGEX = '#\+\+\+ New\n#s';

    /**
     * @see https://regex101.com/r/wtTr3f/1
     */
    private const string NO_NEWLINE_REGEX = '#\\\\ No newline at end of file\n#m';

    /**
     * @see https://regex101.com/r/YqJtOP/1
     */
    private const string SPACING_BRACKET_REGEX = '#\-}\n\+}#';

    /**
     * @see https://regex101.com/r/pEset9/1
     */
    private const string DIFF_LINE_START_REGEX = '#^(\-|\+)#';

    public function clean(string $fileDiff): string
    {
        $fileDiff = Strings::replace($fileDiff, self::DIFF_HEADER_ORIGINAL_REGEX, '');
        $fileDiff = Strings::replace($fileDiff, self::DIFF_HEADER_NEW_REGEX, '');
        $fileDiff = Strings::replace($fileDiff, self::NO_NEWLINE_REGEX, '');

        $fileDiff = rtrim($fileDiff) . PHP_EOL;

        $fileDiff = Strings::replace($fileDiff, self::SPACING_BRACKET_REGEX, '}');

        // nothing to diff here?
        if ($this->hasDiff($fileDiff)) {
            return $fileDiff;
        }

        return Comment::NO_CHANGE_CONTENT;
    }

    private function hasDiff(string $fileDiff): bool
    {
        $fileDiffLines = explode(PHP_EOL, $fileDiff);
        $filter = static fn (string $fileDiffLine): bool => (bool) Strings::match(
            $fileDiffLine,
            self::DIFF_LINE_START_REGEX
        );

        return AtLeast::once($fileDiffLines, $filter);
    }
}
