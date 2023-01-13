<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AboutController extends AbstractController
{
    #[Route(path: 'about', name: RouteName::ABOUT)]
    public function __invoke(Request $request): Response
    {
        return $this->render('homepage/about.twig', [
            'page_title' => 'About Rector',
        ]);
    }
}
