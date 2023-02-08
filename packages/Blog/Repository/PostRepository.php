<?php

declare(strict_types=1);

namespace Rector\Website\Blog\Repository;

use ArrayLookup\Finder as ArrayLookupFinder;
use Rector\Website\Blog\ValueObject\Post;
use Rector\Website\Blog\ValueObjectFactory\PostFactory;
use Rector\Website\Exception\ShouldNotHappenException;
use Symfony\Component\Finder\Finder;
use Symplify\SmartFileSystem\Finder\FinderSanitizer;
use Symplify\SmartFileSystem\SmartFileInfo;

final class PostRepository
{
    /**
     * @var string
     */
    private const POST_DIRECTORY = __DIR__ . '/../../../data/blog/posts';

    /**
     * @var Post[]
     */
    private array $posts = [];

    public function __construct(
        private readonly FinderSanitizer $finderSanitizer,
        private readonly PostFactory $postFactory
    ) {
        $this->createPosts();
    }

    /**
     * @return Post[]
     */
    public function getPosts(): array
    {
        $posts = $this->posts;
        return array_filter($posts, static fn (Post $post): bool => ! $post->isDeprecated());
    }

    public function findBySlug(string $slug): ?Post
    {
        $filter = static fn (Post $post): bool => $post->getSlug() === $slug;
        return ArrayLookupFinder::first($this->posts, $filter);
    }

    /**
     * @return Post[]
     */
    public function fetchLast(int $count): array
    {
        return array_slice($this->getPosts(), 0, $count);
    }

    private function createPosts(): void
    {
        $posts = [];
        foreach ($this->findPostMarkdownFileInfos() as $smartFileInfo) {
            $post = $this->postFactory->createFromFileInfo($smartFileInfo);
            if (isset($posts[$post->getId()])) {
                $message = sprintf(
                    'Post with id "%d" in "%s" file is duplicated. Increase it to higher one',
                    $post->getId(),
                    $smartFileInfo->getRelativeFilePathFromCwd()
                );
                throw new ShouldNotHappenException($message);
            }

            $posts[$post->getId()] = $post;
        }

        $this->posts = $this->sortByDateTimeFromNewest($posts);
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

    /**
     * @param Post[] $posts
     * @return Post[]
     */
    private function sortByDateTimeFromNewest(array $posts): array
    {
        usort(
            $posts,
            static fn (Post $firstPost, Post $secondPost): int => $secondPost->getDateTime() <=> $firstPost->getDateTime()
        );

        return $posts;
    }
}
