<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ForCompaniesController extends AbstractController
{
    #[Route(path: 'for-companies', name: RouteName::FOR_COMPANIES)]
    public function __invoke(Request $request): Response
    {
        return $this->render('homepage/for_companies.twig', [
            'page_title' => 'Hire Rector team to Reduce Costs and Technical Debt',
        ]);
    }
}
