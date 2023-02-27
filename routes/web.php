<?php

declare(strict_types=1);

use App\Http\Controller\AboutController;
use App\Http\Controller\BlogController;
use App\Http\Controller\BookController;
use App\Http\Controller\ContactController;
use App\Http\Controller\DemoController;
use App\Http\Controller\DocumentationController;
use App\Http\Controller\ForCompaniesController;
use App\Http\Controller\HomepageController;
use App\Http\Controller\PostController;
use App\Http\Controller\RssController;
use Illuminate\Support\Facades\Route;
use Rector\Website\Enum\RouteName;

Route::get('laravel-documentation/{section?}', DocumentationController::class)
    ->name(RouteName::DOCUMENTATION);

Route::get('laravel-about', AboutController::class)
    ->name(RouteName::ABOUT);

Route::get('laravel-blog', BlogController::class)
    ->name(RouteName::BLOG);

Route::get('laravel-book', BookController::class)
    ->name(RouteName::BOOK);

Route::get('laravel-laravel-contact', ContactController::class)
    ->name(RouteName::CONTACT);

Route::get('laravel-demo/{uuid}', DemoController::class)
    ->name(RouteName::DEMO_DETAIL);

Route::get('laravel-demo', DemoController::class)
    ->name(RouteName::DEMO);

Route::get('laravel-hire-team', ForCompaniesController::class)
    ->name(RouteName::HIRE_TEAM);

Route::get('laravel-homepage/', HomepageController::class)
    ->name(RouteName::HOMEPAGE);

Route::get('laravel-blog/{postSlug}', PostController::class)
    ->name(RouteName::POST);

Route::get('laravel-rss.xml', RssController::class)
    ->name(RouteName::RSS);
