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
    private const COUNT_REGEX = '~## ~';

    /**
     * @var int
     */
    private const FALLBACK_COUNT = 600;

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
        return (int) $this->cache->get(self::CACHE_KEY, function (ItemInterface $item): int {
            /** @var DateInterval $dateInterval */
            $dateInterval = DateInterval::createFromDateString('+24 hours');

            $item->expiresAfter($dateInterval);

            $sourceContent = $this->smartFileSystem->readFile(self::SOURCE_URL);
            $matches = Strings::matchAll($sourceContent, self::COUNT_REGEX);

            return count($matches) ?? self::FALLBACK_COUNT;
        });
    }
}
