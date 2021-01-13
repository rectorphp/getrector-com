<?php

declare(strict_types=1);

namespace Rector\Website\Blog\Controller;

use Rector\Website\ValueObject\Routing\RouteName;
use Rector\Website\Blog\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PostController extends AbstractController
{
    public function __construct(
        private PostRepository $postRepository
    ) {
    }

    #[Route('blog/{postSlug}', name: RouteName::POST, requirements: [
        'postSlug' => '.+',
    ])]
    public function __invoke(string $postSlug): Response
    {
        $post = $this->postRepository->findBySlug($postSlug);
        if ($post === null) {
            $message = sprintf("Post with slug '%s' not found", $postSlug);
            throw $this->createNotFoundException($message);
        }
        return $this->render('blog/post.twig', [
            'post' => $post,
        ]);
    }
}
