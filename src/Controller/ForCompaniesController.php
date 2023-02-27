<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Rector\Website\Enum\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ForCompaniesController extends AbstractController
{
    #[Route(path: 'hire-team', name: RouteName::HIRE_TEAM)]
    public function __invoke(): Response
    {
        return $this->render('homepage/hire_team.twig', [
            'page_title' => 'Hire the Rector Team to Reduce Costs and Technical Debt',
        ]);
    }
}
