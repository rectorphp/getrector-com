<?php

declare(strict_types=1);

namespace Rector\Website\Blog\Controller;

use Rector\Website\Blog\Repository\PostRepository;
<<<<<<< HEAD
use Rector\Website\ValueObject\Routing\RouteName;
=======
use Rector\Website\ValueObject\RouteName;
>>>>>>> f4f0030... improving project form
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BlogController extends AbstractController
{
    public function __construct(
        private PostRepository $postRepository
    ) {
    }

<<<<<<< HEAD
    #[Route('blog', name: RouteName::BLOG)]
=======
    #[Route(path: 'blog', name: RouteName::BLOG)]
>>>>>>> f4f0030... improving project form
    public function __invoke(): Response
    {
        return $this->render('blog/blog.twig', [
            'posts' => $this->postRepository->getPosts(),
        ]);
    }
}
