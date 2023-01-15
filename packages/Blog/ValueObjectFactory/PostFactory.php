<?php

declare(strict_types=1);

namespace Rector\Website\Blog\ValueObjectFactory;

use DateTimeInterface;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;
use ParsedownExtra;
use Rector\Website\Blog\FileSystem\PathAnalyzer;
use Rector\Website\Blog\ValueObject\Post;
use Rector\Website\Exception\ShouldNotHappenException;
use Symfony\Component\Yaml\Yaml;
use Symplify\SmartFileSystem\SmartFileInfo;

final class PostFactory
{
    /**
     * @var string
     */
    private const SLASHES_WITH_SPACES_REGEX = '(?:---[\s]*[\r\n]+)';

    /**
     * @var string
     */
    private const CONFIG_CONTENT_REGEX = '#^\s*' . self::SLASHES_WITH_SPACES_REGEX . '?(?<config>.*?)' . self::SLASHES_WITH_SPACES_REGEX . '(?<content>.*?)$#s';

    /**
     * @see https://regex101.com/r/gtR8tj/1
     * @var string
     */
    private const HEADLINE_REGEX = '#<h(?<level>\d+)>(?<headline>.*?)<\/h\d+>#';

    public function __construct(
        private readonly ParsedownExtra $parsedownExtra,
        private readonly PathAnalyzer $pathAnalyzer,
    ) {
    }

    public function createFromFileInfo(SmartFileInfo $smartFileInfo): Post
    {
        $matches = Strings::match($smartFileInfo->getContents(), self::CONFIG_CONTENT_REGEX);

        if (! isset($matches['config'])) {
            throw new ShouldNotHappenException();
        }

        $configuration = Yaml::parse($matches['config']);

        $id = $configuration['id'];
        $title = $configuration['title'];
        $perex = $configuration['perex'];

        $slug = $this->pathAnalyzer->getSlug($smartFileInfo);

        $dateTime = $this->pathAnalyzer->detectDate($smartFileInfo);
        if (! $dateTime instanceof DateTimeInterface) {
            throw new ShouldNotHappenException();
        }

        if (! isset($matches['content'])) {
            throw new ShouldNotHappenException();
        }

        $htmlContent = $this->parsedownExtra->parse($matches['content']);
        $htmlContent = $this->decorateHeadlineWithId($htmlContent);

        $updatedSince = isset($configuration['updated_since']) ? new DateTime($configuration['updated_since']) : null;
        $updatedMessage = $configuration['updated_message'] ?? null;

        $deprecatedSince = isset($configuration['deprecated_since']) ? new DateTime(
            $configuration['deprecated_since']
        ) : null;
        $deprecatedMessage = $configuration['deprecated_message'] ?? null;

        $sinceRector = isset($configuration['since_rector']) ? (string) $configuration['since_rector'] : null;

        return new Post(
            $id,
            $title,
            $slug,
            $dateTime,
            $perex,
            $htmlContent,
            $updatedSince,
            $updatedMessage,
            $deprecatedSince,
            $deprecatedMessage,
            $sinceRector
        );
    }

    /**
     * Before: <h1>Hey</h1>
     *
     * After: <h1 id="hey">Hey</h1>
     *
     * Then the headline can be anchored in url as "#hey"
     */
    private function decorateHeadlineWithId(string $htmlContent): string
    {
        return Strings::replace($htmlContent, self::HEADLINE_REGEX, static function ($matches): string {
            $level = $matches['level'];
            $headline = $matches['headline'];
            $idValue = Strings::webalize($headline);
            return sprintf('<h%d id="%s">%s</h%d>', $level, $idValue, $headline, $level);
        });
    }
}
