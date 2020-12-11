<?php

declare(strict_types=1);

namespace Rector\Website\Admin\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DashboardController extends AbstractDashboardController
{
    #[Route(path: 'admin', name: 'admin')]
    public function index(): Response
    {
        return parent::index();
    }
}
