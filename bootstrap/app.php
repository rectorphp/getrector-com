<?php

declare(strict_types=1);

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Exceptions\Handler;

$application = new Application(__DIR__ . '/..');

$application->singleton(
    Kernel::class,
    \Rector\Website\Http\HttpKernel::class
);

$application->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    Illuminate\Foundation\Console\Kernel::class,
);

$application->singleton(
    ExceptionHandler::class,
    Handler::class
);

return $application;
