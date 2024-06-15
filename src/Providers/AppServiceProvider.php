<?php

declare(strict_types=1);

namespace Rector\Website\Providers;

use Illuminate\Support\ServiceProvider;
use Rector\Website\Sets\RectorSetsTreeProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RectorSetsTreeProvider::class);
    }
}
