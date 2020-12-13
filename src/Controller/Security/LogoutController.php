<?php

declare(strict_types=1);

namespace Rector\Website\Controller\Security;

use Exception;
use Rector\Website\Exception\ShouldNotHappenException;
use Rector\Website\ValueObject\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class LogoutController extends AbstractController
{
    #[Route(path: '/logout', name: RouteName::LOGOUT)]
    public function __invoke(Request $request): Response
    {
        // this is handled internally by Symfony security; not sure why this magic is needed here :/
        throw new ShouldNotHappenException('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
