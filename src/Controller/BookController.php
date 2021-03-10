<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BookController extends AbstractController
{
    #[Route(path: 'book', name: RouteName::BOOK)]
    public function __invoke(): Response
    {
        return $this->render('homepage/book.twig', [
            'page_title' => 'The Power of Automated Refactoring'
        ]);
    }
}
