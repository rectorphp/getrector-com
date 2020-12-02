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
     * @see https://regex101.com/r/BTdMQa/2
     * @var string
     */
    private const COUNT_REGEX = '#\b(?<count>\d+) Rules Overview\b#';

    /**
     * @var int
     */
    private const FALLBACK_COUNT = 620;

    /**
     * @var string
     */
    private const CACHE_KEY = 'rectors_count';

    public function __construct(private CacheInterface $cache, private SmartFileSystem $smartFileSystem)
    {
    }

    public function provide(): int
    {
        return (int) $this->cache->get(self::CACHE_KEY, function (ItemInterface $item): int {
            /** @var DateInterval $dateInterval */
            $dateInterval = DateInterval::createFromDateString('+24 hours');

            $item->expiresAfter($dateInterval);

            $sourceContent = $this->smartFileSystem->readFile(self::SOURCE_URL);
            $matches = Strings::matchAll($sourceContent, self::COUNT_REGEX);

            return $matches['count'] ?? self::FALLBACK_COUNT;
        });
    }
}
