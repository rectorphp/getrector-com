<?php

declare(strict_types=1);

namespace Rector\Website\Controller\Security;

use Rector\Website\ValueObject\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @see https://symfony.com/doc/current/bundles/EasyAdminBundle/dashboards.html#login-form-template
 */
final class LoginController extends AbstractController
{
    public function __construct(
        private AuthenticationUtils $authenticationUtils
    ) {
    }

    #[Route(path: '/login', name: RouteName::LOGIN)]
    public function __invoke(): Response
    {
        return $this->render('@EasyAdmin/page/login.html.twig', [
            // parameters usually defined in Symfony login forms
            'error' => $this->authenticationUtils->getLastAuthenticationError(),
            'last_username' => $this->authenticationUtils->getLastUsername(),

            'page_title' => 'Cleaning Lady List Login',

            // the string used to generate the CSRF token. If you don't define
            // this parameter, the login form won't include a CSRF token
            'csrf_token_intention' => 'authenticate',
        ]);
    }
}
