<?php

declare(strict_types=1);

namespace Rector\Website\Http\Controller\Blog;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Rector\Website\Entity\Post;
use Rector\Website\Repository\PostRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PostController extends Controller
{
    public function __construct(
        private readonly PostRepository $postRepository,
    ) {
    }

    public function __invoke(string $postSlug): View
    {
        $post = $this->postRepository->findBySlug($postSlug);
        if (! $post instanceof Post) {
            $message = sprintf("Post with slug '%s' not found", $postSlug);
            throw new NotFoundHttpException($message);
        }

        return \view('blog/post', [
            'post' => $post,
        ]);
    }
}
