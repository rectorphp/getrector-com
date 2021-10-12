<?php

declare(strict_types=1);

namespace Rector\Website\Blog\Controller;

use Rector\Website\Blog\Repository\PostRepository;
use Rector\Website\Blog\ValueObject\Post;

use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class PostController extends AbstractController
{
    public function __construct(
        private PostRepository $postRepository,
    ) {
    }

    #[Route(path: 'blog/{postSlug}', name: RouteName::POST, requirements: [
        'postSlug' => '(\d+\/\d+.+|[\w\-]+)',
    ])]
    public function __invoke(string $postSlug): Response
    {
        $post = $this->postRepository->findBySlug($postSlug);
        if (! $post instanceof Post) {
            $message = sprintf("Post with slug '%s' not found", $postSlug);
            throw new NotFoundHttpException($message);
        }

        return $this->render('blog/post.twig', [
            'post' => $post,
        ]);
    }
}
