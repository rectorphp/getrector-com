<?php

declare(strict_types=1);

use Rector\Website\ValueObject\RouteName;
use Rector\Website\ValueObject\Symfony\SecurityExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Security\Core\User\User;

/**
 * Inspiration
 *
 * @see https://github.com/pehapkari/archiv.pehapkari.cz/blob/21124121cfbd7d6988fc168438fa6e5883d992b2/config/packages/security.yaml
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension(SecurityExtension::NAME, [
        'access_denied_url' => '/access-denied',

        SecurityExtension::ACCESS_CONTROL => [
            [
                // allow access to "/admin" only to logged-in
                'path' => '^/admin',
                'roles' => ['ROLE_ADMIN'],
            ],
        ],

        SecurityExtension::ENCODERS => [
            User::class => [
                'algorithm' => 'auto',
            ],
        ],

        SecurityExtension::PROVIDERS => [
            // @see https://symfony.com/doc/current/security/user_provider.html#memory-user-provider
            'admin_users' => [
                'memory' => [
                    'users' => [
                        // name => ...
                        'admin' => [
                            // secret password
                            'password' => '$argon2id$v=19$m=65536,t=4,p=1$L1C6tcIJP1Nzjlri3IUlTQ$R+aMMEG6eKfwCUrCbWTWSeIzE/XEJCLCkhgSD/l3uKg',
                            'roles' => ['ROLE_ADMIN'],
                        ],
                    ],
                ],
            ],
        ],

        SecurityExtension::FIREWALLS => [
            'dev' => [
                'pattern' => '^/(_(profiler|wdt)|css|images|js)/',
                'security' => false,
            ],

            'main' => [
                'pattern' => '^/',
                'anonymous' => true,

                // This allows the user to login by submitting a username and password
                // Reference: https://symfony.com/doc/current/security/form_login_setup.html
                'form_login' => [
                    // The route name that the login form submits to
                    'check_path' => RouteName::LOGIN,
                    // The name of the route where the login form lives
                    // When the user tries to access a protected page, they are redirected here
                    'login_path' => RouteName::LOGIN,
                    // Secure the login form against CSRF
                    // Reference': https://symfony.com/doc/current/security/csrf_in_login_form.html
                    'csrf_token_generator' => 'security.csrf.token_manager',
                    // https://symfony.com/doc/current/security/form_login.html#changing-the-default-page
                    'default_target_path' => RouteName::ADMIN,
                ],
                'logout' => [
                    // The route name the user can go to in order to logout
                    'path' => 'logout',
                    // The name of the route to redirect to after logging out
                    'target' => RouteName::HOMEPAGE,
                ],
            ],
        ],
    ]);
};
