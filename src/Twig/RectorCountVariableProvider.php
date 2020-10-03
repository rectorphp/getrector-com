<?php

declare(strict_types=1);

namespace Rector\Website\Twig;

use DateInterval;
use Nette\Utils\Strings;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symplify\SmartFileSystem\SmartFileSystem;

final class RectorCountVariableProvider
{
    /**
     * @var string
     */
    private const SOURCE_URL = 'https://raw.githubusercontent.com/rectorphp/rector/master/docs/rector_rules_overview.md';

    /**
     * @see https://regex101.com/r/Q1lNxP/1
     * @var string
     */
    private const COUNT_REGEX = '#\b(?<count>\d+)\b#';

    /**
     * @var int
     */
    private const FALLBACK_COUNT = 400;

    /**
     * @var string
     */
    private const CACHE_KEY = 'rectors_count';

    private CacheInterface $cache;

    private SmartFileSystem $smartFileSystem;

    public function __construct(CacheInterface $cache, SmartFileSystem $smartFileSystem)
    {
        $this->cache = $cache;
        $this->smartFileSystem = $smartFileSystem;
    }

    public function provide(): int
    {
        return (int) $this->cache->get(self::CACHE_KEY, function (ItemInterface $item) {
            $item->expiresAfter(DateInterval::createFromDateString('+24 hours'));

            $sourceContent = $this->smartFileSystem->readFile(self::SOURCE_URL);
            $matches = Strings::match($sourceContent, self::COUNT_REGEX);

            return $matches['count'] ?? self::FALLBACK_COUNT;
        });
    }
}
