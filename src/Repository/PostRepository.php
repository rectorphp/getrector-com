<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
use App\EntityFactory\PostFactory;
use App\Exception\ShouldNotHappenException;
use ArrayLookup\Finder as ArrayLookupFinder;
use Webmozart\Assert\Assert;

final class PostRepository
{
    /**
     * @var string
     */
    private const POST_DIRECTORY = __DIR__ . '/../../resources/blog/posts';

    /**
     * @var Post[]
     */
    private array $posts = [];

    public function __construct(
        private readonly PostFactory $postFactory
    ) {
        $this->createPosts();
    }

    /**
     * @return Post[]
     */
    public function getPosts(): array
    {
        return $this->posts;
    }

    public function findByTitle(string $title): ?Post
    {
        $filter = static fn (Post $post): bool => $post->getTitle() === $title;
        return ArrayLookupFinder::first($this->posts, $filter);
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
        return array_slice($this->posts, 0, $count);
    }

    private function createPosts(): void
    {
        $posts = [];

        $postFilePaths = $this->findPostsFilePaths();
        Assert::notEmpty($postFilePaths);

        foreach ($postFilePaths as $postFilePath) {
            $post = $this->postFactory->createFromFilePath($postFilePath);

            if (isset($posts[$post->getId()])) {
                $message = sprintf(
                    'Post with id "%d" in "%s" file is duplicated. Increase it to higher one',
                    $post->getId(),
                    $postFilePath
                );
                throw new ShouldNotHappenException($message);
            }

            $posts[$post->getId()] = $post;
        }

        $this->posts = $this->sortByDateTimeFromNewest($posts);
    }

    /**
     * @return string[]
     */
    private function findPostsFilePaths(): array
    {
        return (array) glob(self::POST_DIRECTORY . '/*/*.md');
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
