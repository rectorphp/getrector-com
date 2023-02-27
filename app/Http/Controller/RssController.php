<?php

declare(strict_types=1);

namespace App\Http\Controller;

use DateTimeInterface;
use Rector\Website\Entity\Post;
use Rector\Website\Repository\PostRepository;

final class RssController extends \Illuminate\Routing\Controller
{
    public function __construct(
        private readonly PostRepository $postRepository,
    ) {
    }

    public function __invoke(): \Illuminate\Contracts\View\View
    {
        $posts = $this->postRepository->getPosts();

        return \view('blog/rss', [
            'posts' => $posts,
            'most_recent_post_date_time' => $this->getMostRecentPostDateTime($posts),
        ]);
    }

    /**
     * @param Post[] $posts
     */
    private function getMostRecentPostDateTime(array $posts): DateTimeInterface
    {
        $firstPostKey = array_key_first($posts);
        $firstPost = $posts[$firstPostKey];

        return $firstPost->getDateTime();
    }
}
