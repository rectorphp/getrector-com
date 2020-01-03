<?php

declare(strict_types=1);

namespace Rector\Website\Twig;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;

final class RectorCountVariableProvider
{
    /**
     * @var string
     */
    private const SOURCE_URL = 'https://raw.githubusercontent.com/rectorphp/rector/master/docs/AllRectorsOverview.md';


    /**
     * @var int
     */
    private const FALLBACK_COUNT = 400;

    public function provide(): int
    {
        $sourceContent = FileSystem::read(self::SOURCE_URL);

        $matches = Strings::match($sourceContent, '#\b(?<count>\d+)\b#');

        $count = $matches['count'] ?? self::FALLBACK_COUNT;

        return (int) $count;
    }
}
