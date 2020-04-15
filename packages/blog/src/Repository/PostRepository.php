<?php

declare(strict_types=1);

namespace Rector\Website\Blog\Repository;

use Rector\Website\Blog\ValueObject\Post;
use Rector\Website\Blog\ValueObjectFactory\PostFactory;
use Symfony\Component\Finder\Finder;
use Symplify\SmartFileSystem\Finder\FinderSanitizer;
use Symplify\SmartFileSystem\SmartFileInfo;

final class PostRepository
{
    /**
     * @var string
     */
    private const POST_DIRECTORY = __DIR__ . '/../../config/data';

    private FinderSanitizer $finderSanitizer;

    private PostFactory $postFactory;

    /**
     * @var Post[]
     */
    private $posts = [];

    public function __construct(FinderSanitizer $finderSanitizer, PostFactory $postFactory)
    {
        $this->finderSanitizer = $finderSanitizer;
        $this->postFactory = $postFactory;
    }

    /**
     * @return Post[]
     */
    public function fetchAll(): array
    {
        foreach ($this->findPostMarkdownFileInfos() as $smartFileInfo) {
            $post = $this->postFactory->createFromFileInfo($smartFileInfo);
            $this->posts[$post->getId()] = $post;
        }

        // sort from newest
        usort($this->posts, function (Post $firstPost, Post $secondPost): int {
            return $secondPost->getDateTime() <=> $firstPost->getDateTime();
        });

        return $this->posts;
    }

    public function findBySlug(string $slug): ?Post
    {
        foreach ($this->fetchAll() as $post) {
            if ($post->getSlug() === $slug) {
                return $post;
            }
        }

        return null;
    }

    /**
     * @return SmartFileInfo[]
     */
    private function findPostMarkdownFileInfos(): array
    {
        $finder = new Finder();
        $finder->files()
            ->in(self::POST_DIRECTORY)
            ->name('*.md');

        return $this->finderSanitizer->sanitize($finder);
    }
}
