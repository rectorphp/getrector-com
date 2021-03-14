<?php

declare(strict_types=1);

namespace Rector\Website\Blog\Controller;

use Rector\Website\Blog\Repository\PostRepository;
use Rector\Website\Twig\ResponseRenderer;
use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BlogController
{
    public function __construct(
        private PostRepository $postRepository,
        private ResponseRenderer $responseRenderer
    ) {
    }

    #[Route(path: 'blog', name: RouteName::BLOG)]
    public function __invoke(): Response
    {
        return $this->responseRenderer->render('blog/blog.twig', [
            'posts' => $this->postRepository->getPosts(),
        ]);
    }
}
