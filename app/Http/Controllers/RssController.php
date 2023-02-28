<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use DateTimeInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Rector\Website\Entity\Post;
use Rector\Website\Repository\PostRepository;

final class RssController extends Controller
{
    public function __construct(
        private readonly PostRepository $postRepository,
    ) {
    }

    public function __invoke(): View
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
