<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Rector\Website\Twig\ResponseRenderer;
use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ForCompaniesController
{
    public function __construct(
        private ResponseRenderer $responseRenderer,
    ) {
    }

    #[Route(path: 'for-companies', name: RouteName::FOR_COMPANIES)]
    public function __invoke(Request $request): Response
    {
        return $this->responseRenderer->render('homepage/for_companies.twig', [
            'title' => 'Do you have a Successful Project, but old Code Programmers Hate',
        ]);
    }
}
