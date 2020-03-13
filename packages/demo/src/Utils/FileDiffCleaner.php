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
    private const DIFF_START_PATTERN = '#^.*?@@\n#Us';

    public function clean(string $fileDiff): string
    {
        $fileDiff = Strings::replace($fileDiff, self::DIFF_START_PATTERN);
        $fileDiff = Strings::replace($fileDiff, '#\\\\ No newline at end of file\n#m');

        return trim($fileDiff) . PHP_EOL;
    }
}
