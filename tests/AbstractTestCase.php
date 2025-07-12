<?php

declare(strict_types=1);

namespace App\Tests;

use Override;
use App\DependencyInjection\DependencyInjectionContainerFactory;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase;

abstract class AbstractTestCase extends TestCase
{
    #[Override]
    public function createApplication(): Application
    {
        return DependencyInjectionContainerFactory::create();
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
