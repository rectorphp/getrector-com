<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

<<<<<<< HEAD
use Rector\Website\Enum\RouteName;
=======
use Rector\Website\ValueObject\RouteName;
>>>>>>> 044a866 (shorter name)
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AboutController extends AbstractController
{
    #[Route(path: 'about', name: RouteName::ABOUT)]
    public function __invoke(): Response
    {
        return $this->render('homepage/about.twig', [
            'page_title' => 'About Rector',
        ]);
    }
}
