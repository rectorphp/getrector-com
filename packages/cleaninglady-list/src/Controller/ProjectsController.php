<?php

declare(strict_types=1);

namespace Rector\Website\CleaningLadyList\Controller;

use Rector\Website\ValueObject\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProjectsController extends AbstractController
{
    #[Route(path: 'cleaning-lady-list', name: RouteName::PROJECTS)]
    public function __invoke(Request $request): Response
    {
        return $this->render('project/projects.twig', [
            'page_title' => 'Cleaning Lady List',
        ]);
    }
}
