<?php

declare(strict_types=1);

namespace App\FileSystem;

use App\Exception\ShouldNotHappenException;
use DateTimeInterface;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;

final class PathAnalyzer
{
    /**
     * @see https://regex101.com/r/UD1dMk/1
     */
    private const string DATE_REGEX = '(?<year>\d{4})-(?<month>\d{2})-(?<day>\d{2})';

    /**
     * @see https://regex101.com/r/2TMMR2/1
     */
    private const string NAME_REGEX = '(?<name>[\w\d-]*)';

    public function detectDate(string $filePath): ?DateTimeInterface
    {
        $match = Strings::match($filePath, '#' . self::DATE_REGEX . '#');
        if ($match === null) {
            return null;
        }

        $date = sprintf('%d-%d-%d', $match['year'], $match['month'], $match['day']);

        return DateTime::from($date);
    }

    public function getSlug(string $filePath): string
    {
        $dateTime = $this->detectDate($filePath);

        if (! $dateTime instanceof DateTimeInterface) {
            throw new ShouldNotHappenException();
        }

        $dateAndNamePattern = sprintf('#%s-%s#', self::DATE_REGEX, self::NAME_REGEX);
        $match = (array) Strings::match($filePath, $dateAndNamePattern);
        return (string) $match['name'];
    }
}
