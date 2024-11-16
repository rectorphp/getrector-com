<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Console\Kernel;

/**
 * @api used in factory and bin files
 */
final class DependencyInjectionContainerFactory
{
    public static function create(): Container
    {
        /** @var Application $application */
        $application = require __DIR__ . '/../../bootstrap/app.php';

        /** @var Kernel $consoleKernel */
        $consoleKernel = $application->make(Kernel::class);
        $consoleKernel->bootstrap();

        return $application;
    }
}
