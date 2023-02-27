<?php

declare(strict_types=1);

use App\Providers\RouteServiceProvider;
use Illuminate\Cache\CacheServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Foundation\Providers\ConsoleSupportServiceProvider;
use Illuminate\Foundation\Providers\FoundationServiceProvider;
use Illuminate\Session\SessionServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;
use Illuminate\Validation\ValidationServiceProvider;
use Illuminate\View\ViewServiceProvider;
use TomasVotruba\PunchCard\AppConfig;

return AppConfig::make()
    ->name('getrector-com')
    ->env(env('APP_ENV', 'production'))
    ->debug((bool) env('APP_DEBUG', false))
    ->key(env('APP_KEY'))
    ->url(env('APP_URL', 'http://localhost'))
    ->assetUrl(env('ASSET_URL'))
    ->timezone('UTC')
    ->locale('en')
    ->providers([
        // Laravel Framework Service Providers...
        CacheServiceProvider::class,
        ConsoleSupportServiceProvider::class,
        FilesystemServiceProvider::class,
        FoundationServiceProvider::class,
        ViewServiceProvider::class,
        ValidationServiceProvider::class,
        SessionServiceProvider::class,
        RouteServiceProvider::class,
        TranslationServiceProvider::class,
    ])
    ->toArray();
