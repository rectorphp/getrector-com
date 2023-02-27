<?php

declare(strict_types=1);

namespace Rector\Website\Tests;

use App\Http\HttpKernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase;

abstract class AbstractTestCase extends TestCase
{
    public function createApplication(): Application
    {
        $application = require __DIR__ . '/../bootstrap/app.php';

        /** @var HttpKernel $httpKernel */
        $httpKernel = $application->make(HttpKernel::class);
        $httpKernel->bootstrap();

        return $application;
    }
}
