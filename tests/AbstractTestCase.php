<?php

declare(strict_types=1);

namespace Rector\Website\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase;

abstract class AbstractTestCase extends TestCase
{
    public function createApplication(): Application
    {
        $application = require __DIR__ . '/../bootstrap/app.php';

        /** @var Kernel $consoleKernel */
        $consoleKernel = $application->make(Kernel::class);
        $consoleKernel->bootstrap();

        return $application;
    }

    /**
     * @template TType as object
     * @param class-string<TType> $classType
     * @return TType
     */
    public function make(string $classType): object
    {
        return app()->make($classType);
    }
}
