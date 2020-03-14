<?php

declare(strict_types=1);

namespace Rector\Website\Twig;

use DateInterval;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

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

    /**
     * @var string
     */
    private const CACHE_KEY = 'rectors_count';

    private CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function provide(): int
    {
        return (int) $this->cache->get(self::CACHE_KEY, static function (ItemInterface $item) {
            $item->expiresAfter(DateInterval::createFromDateString('+24 hours'));

            $sourceContent = FileSystem::read(self::SOURCE_URL);

            $matches = Strings::match($sourceContent, '#\b(?<count>\d+)\b#');

            return $matches['count'] ?? self::FALLBACK_COUNT;
        });
    }
}
