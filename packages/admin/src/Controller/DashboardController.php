<?php

declare(strict_types=1);

namespace Rector\Website\Admin\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Menu\MenuItemInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Rector\Website\CleaningLadyList\Entity\Checkbox;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DashboardController extends AbstractDashboardController
{
    #[Route(path: 'admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('@EasyAdmin/page/content.html.twig');
    }

    /**
     * @return MenuItemInterface[]
     */
    public function configureMenuItems(): array
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToCrud('Checkbox', 'fa fa-list', Checkbox::class),
        ];
    }
}
