<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Rector\Website\Twig\ResponseRenderer;
use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BankDetailsController
{
    public function __construct(
        private ResponseRenderer $responseRenderer
    ) {
    }

    #[Route(path: 'bank-details', name: RouteName::BANK_DETAILS)]
    public function __invoke(): Response
    {
        return $this->responseRenderer->render('homepage/bank_details.twig');
    }
}
