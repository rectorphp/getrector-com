<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Utils;

use Nette\Utils\Strings;

/**
 * @see \Rector\Website\Demo\Tests\Utils\FileDiffCleanerTest
 */
final class FileDiffCleaner
{
    /**
     * @see https://regex101.com/r/sI6GVY/1/
     * @var string
     */
    private const DIFF_START_REGEX = '#^.*?@@\n#Us';

    /**
     * @see https://regex101.com/r/wtTr3f/1
     * @var string
     */
    private const NO_NEWLINE_REGEX = '#\\\\ No newline at end of file\n#m';

    public function clean(string $fileDiff): string
    {
        $fileDiff = Strings::replace($fileDiff, self::DIFF_START_REGEX, '');
        $fileDiff = Strings::replace($fileDiff, self::NO_NEWLINE_REGEX, '');

        return trim($fileDiff) . PHP_EOL;
    }
}
