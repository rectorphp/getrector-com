<?php

declare(strict_types=1);

namespace Rector\Website\Tests;

use App\Http\HttpKernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Http\Request;

abstract class AbstractTestCase extends TestCase
{
    public function createApplication(): Application
    {
        $application = require __DIR__ . '/../bootstrap/app.php';

        /** @var HttpKernel $httpKernel */
        $httpKernel = $application->make(HttpKernel::class);
        $httpKernel->bootstrap();

        // setup for tests, see https://chat.openai.com/chat/2535e131-d527-42f6-b7f4-a45fd9510958
        $request = new Request();
        $application->instance('request', $request);

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
