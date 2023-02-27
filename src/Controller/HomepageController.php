<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Rector\Website\Repository\PostRepository;
use Rector\Website\ValueObject\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomepageController extends AbstractController
{
    public function __construct(
        private readonly PostRepository $postRepository,
    ) {
    }

    #[Route(path: '/', name: RouteName::HOMEPAGE)]
    public function __invoke(): Response
    {
        return $this->render('homepage/homepage.twig', [
            'page_title' => "We'll Speed Up Your Development Process by 300%",
            'last_5_posts' => $this->postRepository->fetchLast(5),
        ]);
    }
}
