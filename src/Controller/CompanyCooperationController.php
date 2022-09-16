<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CompanyCooperationController extends AbstractController
{
    #[Route(path: 'company-cooperation', name: RouteName::COMPANY_COOPERATION)]
    public function __invoke(Request $request): Response
    {
        return $this->render('homepage/company_cooperation.twig', [
            'title' => 'How do we Cooperate with Companies?',
        ]);
    }
}
