<?php

declare(strict_types=1);

namespace Rector\Website\Blog\ValueObjectFactory;

use Nette\Utils\Strings;
use ParsedownExtra;
use Rector\Website\Blog\FileSystem\PathAnalyzer;
use Rector\Website\Blog\ValueObject\Post;
use Rector\Website\Exception\ShouldNotHappenException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Yaml\Yaml;
use Symplify\SmartFileSystem\SmartFileInfo;

final class PostFactory
{
    /**
     * @var string
     */
    private const SLASHES_WITH_SPACES_PATTERN = '(?:---[\s]*[\r\n]+)';

    /**
     * @var string
     */
    private const CONFIG_CONTENT_PATTERN = '#^\s*' . self::SLASHES_WITH_SPACES_PATTERN . '?(?<config>.*?)' . self::SLASHES_WITH_SPACES_PATTERN . '(?<content>.*?)$#s';

    private ParsedownExtra $parsedownExtra;

    private PathAnalyzer $pathAnalyzer;

    private RouterInterface $router;

    private string $projectDir;

    private string $siteUrl;

    public function __construct(
        ParsedownExtra $parsedownExtra,
        PathAnalyzer $pathAnalyzer,
        RouterInterface $router,
        string $siteUrl,
        string $projectDir
    ) {
        $this->parsedownExtra = $parsedownExtra;
        $this->pathAnalyzer = $pathAnalyzer;
        $this->router = $router;
        $this->siteUrl = rtrim($siteUrl, '/');
        $this->projectDir = $projectDir;
    }

    public function createFromFileInfo(SmartFileInfo $smartFileInfo): Post
    {
        $matches = Strings::match($smartFileInfo->getContents(), self::CONFIG_CONTENT_PATTERN);

        if (! isset($matches['config'])) {
            throw new ShouldNotHappenException();
        }

        $configuration = Yaml::parse($matches['config']);

        $id = $configuration['id'];
        $title = $configuration['title'];
        $perex = $configuration['perex'];

        $slug = $this->pathAnalyzer->getSlug($smartFileInfo);

        $dateTime = $this->pathAnalyzer->detectDate($smartFileInfo);
        if ($dateTime === null) {
            throw new ShouldNotHappenException();
        }

        if (! isset($matches['content'])) {
            throw new ShouldNotHappenException();
        }

        $htmlContent = $this->parsedownExtra->parse($matches['content']);
        $sourceRelativePath = $this->getSourceRelativePath($smartFileInfo);
        $absoluteUrl = $this->createAbsoluteUrl($slug);

        return new Post($id, $title, $slug, $dateTime, $perex, $htmlContent, $sourceRelativePath, $absoluteUrl);
    }

    private function getSourceRelativePath(SmartFileInfo $smartFileInfo): string
    {
        $relativeFilePath = $smartFileInfo->getRelativeFilePath();
        return ltrim($relativeFilePath, './');
    }

    private function createAbsoluteUrl(string $slug): string
    {
        $siteUrl = rtrim($this->siteUrl, '/');

        return $siteUrl . $this->router->generate('post', ['postSlug' => $slug]);
    }
}
