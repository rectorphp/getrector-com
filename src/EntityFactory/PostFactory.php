<?php

declare(strict_types=1);

namespace App\EntityFactory;

use App\Entity\Post;
use App\Exception\ShouldNotHappenException;
use App\FileSystem\PathAnalyzer;
use DateTimeInterface;
use Nette\Utils\DateTime;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Symfony\Component\Yaml\Yaml;
use Webmozart\Assert\Assert;

final readonly class PostFactory
{
    private const string SLASHES_WITH_SPACES_REGEX = '(?:---[\s]*[\r\n]+)';

    private const string CONFIG_CONTENT_REGEX = '#^\s*' . self::SLASHES_WITH_SPACES_REGEX . '?(?<config>.*?)' . self::SLASHES_WITH_SPACES_REGEX . '(?<content>.*?)$#s';

    public function __construct(
        private PathAnalyzer $pathAnalyzer,
    ) {
    }

    public function createFromFilePath(string $filePath): Post
    {
        $fileContents = FileSystem::read($filePath);

        $matches = Strings::match($fileContents, self::CONFIG_CONTENT_REGEX);
        if (! isset($matches['config'])) {
            throw new ShouldNotHappenException();
        }

        $configuration = Yaml::parse($matches['config']);

        $id = $configuration['id'];
        $title = $configuration['title'];
        $perex = $configuration['perex'];

        $slug = $this->pathAnalyzer->getSlug($filePath);

        $dateTime = $this->pathAnalyzer->detectDate($filePath);
        if (! $dateTime instanceof DateTimeInterface) {
            throw new ShouldNotHappenException();
        }

        Assert::keyExists($matches, 'content');
        $htmlContent = $matches['content'];

        $updatedAt = isset($configuration['updated_at']) ? new DateTime($configuration['updated_at']) : null;
        $updatedMessage = $configuration['updated_message'] ?? null;
        $author = $configuration['author'] ?? 'tomasvotruba';

        $sinceRector = isset($configuration['since_rector']) ? (string) $configuration['since_rector'] : null;

        return new Post(
            $id,
            $title,
            $slug,
            $dateTime,
            $perex,
            $htmlContent,
            $author,
            $updatedAt,
            $updatedMessage,
            $sinceRector
        );
    }
}
