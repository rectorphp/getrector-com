<?php

declare(strict_types=1);

namespace Rector\Website\Http\Controllers;

use DateTimeInterface;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Rector\Website\Entity\Post;
use Rector\Website\Repository\PostRepository;

final class RssController extends Controller
{
    public function __construct(
        private readonly PostRepository $postRepository,
    ) {
    }

    public function __invoke(): Response
    {
        $posts = $this->postRepository->getPosts();

        return response()
            ->view('blog/rss', [
                'posts' => $posts,
                'most_recent_post_date_time' => $this->getMostRecentPostDateTime($posts),
            ])
            ->header('Content-Type', 'text/xml');
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
