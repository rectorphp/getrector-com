<?php

declare(strict_types=1);

namespace App\Controller\Blog;

use App\Entity\Post;
use App\Repository\PostRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PostController extends Controller
{
    public function __invoke(string $postSlug, PostRepository $postRepository): View
    {
        $post = $postRepository->findBySlug($postSlug);
        if (! $post instanceof Post) {
            $message = sprintf("Post with slug '%s' not found", $postSlug);
            throw new NotFoundHttpException($message);
        }

        return \view('blog/post', [
            'post' => $post,
            'codeMirror' => true,
            'metaTitle' => $post->getClearTitle() . ' | Rector',
        ]);
    }
}
