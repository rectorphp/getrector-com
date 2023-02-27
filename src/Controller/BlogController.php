<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Rector\Website\Enum\RouteName;
use Rector\Website\Repository\PostRepository;
<<<<<<< HEAD
=======
use Rector\Website\ValueObject\RouteName;
>>>>>>> 044a866 (shorter name)
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BlogController extends AbstractController
{
    public function __construct(
        private readonly PostRepository $postRepository,
    ) {
    }

    #[Route(path: 'blog', name: RouteName::BLOG)]
    public function __invoke(): Response
    {
        return $this->render('blog/blog.twig', [
            'posts' => $this->postRepository->getPosts(),
        ]);
    }
}
