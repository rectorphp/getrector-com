<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

$application = Application::configure()
    ->withProviders()
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->create();

$application->useAppPath(__DIR__ . '/../src');

return $application;


//$application->useAppPath(__DIR__ . '/../src');
//
//$application->singleton(
//    Kernel::class,
//    \Rector\Website\Http\HttpKernel::class
