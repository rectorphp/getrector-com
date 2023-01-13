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
    #[Route(path: 'hire-team', name: RouteName::HIRE_TEAM)]
    public function __invoke(Request $request): Response
    {
        // BC layer
        if ($request->attributes->get('_route') === RouteName::FOR_COMPANIES) {
            return $this->redirectToRoute(RouteName::HIRE_TEAM);
        }

        return $this->render('homepage/hire_team.twig', [
            'page_title' => 'Hire Rector team to Reduce Costs and Technical Debt',
        ]);
    }
}
