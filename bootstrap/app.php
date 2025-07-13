<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;

$application = Application::configure()
    ->withProviders([\App\Providers\AppServiceProvider::class])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
    )
    ->withCommands([
        __DIR__ . '/../src/Console/Commands'
    ])
    ->withMiddleware(callback: function (Middleware $middleware) {
        if (env('APP_ENV') === 'dev') {
            // Enable reverse proxy for trusted proxies in development environments.
            // Set TRUSTED_PROXIES in your .env file as a comma-separated list of IPs.
            $middleware->trustProxies(env('TRUSTED_PROXIES', '127.0.0.1,::1'));
        }
    })
    ->withExceptions(function (Exceptions $exceptions) {
    })
    ->create();

$application->useAppPath(__DIR__ . '/../src');

return $application;
