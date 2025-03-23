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
    ->withMiddleware(function (Middleware $middleware) {
    })
    ->withExceptions(function (Exceptions $exceptions) {
    })
    ->create();

$application->useAppPath(__DIR__ . '/../src');

return $application;
