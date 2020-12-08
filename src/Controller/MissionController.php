<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MissionController extends AbstractController
{
    #[Route(path: 'mission', name: 'mission')]
    public function __invoke(): Response
    {
        return $this->render('homepage/mission.twig');
    }
}
