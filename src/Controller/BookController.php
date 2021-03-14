<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Rector\Website\Twig\ResponseRenderer;
use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BookController
{
    public function __construct(
        private ResponseRenderer $responseRenderer
    ) {
    }

    #[Route(path: 'book', name: RouteName::BOOK)]
    public function __invoke(): Response
    {
        return $this->responseRenderer->render('homepage/book.twig', [
            'page_title' => 'The Power of Automated Refactoring',
        ]);
    }
}
