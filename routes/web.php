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

Route::get('documentation/{section?}', DocumentationController::class)
    ->name(RouteName::DOCUMENTATION);

Route::get('about', AboutController::class)
    ->name(RouteName::ABOUT);

Route::get('blog', BlogController::class)
    ->name(RouteName::BLOG);

Route::get('book', BookController::class)
    ->name(RouteName::BOOK);

Route::get('contact', ContactController::class)
    ->name(RouteName::CONTACT);

Route::get('demo/{uuid}', DemoController::class)
    ->name(RouteName::DEMO_DETAIL);

Route::post('process-demo', \App\Http\Controller\ProcessDemoFormController::class)
    ->name(RouteName::PROCESS_DEMO_FORM);

Route::get('demo', DemoController::class)
    ->name(RouteName::DEMO);

Route::get('hire-team', ForCompaniesController::class)
    ->name(RouteName::HIRE_TEAM);

Route::get('/', HomepageController::class)
    ->name(RouteName::HOMEPAGE);

Route::get('blog/{postSlug}', PostController::class)
    ->name(RouteName::POST);

Route::get('rss.xml', RssController::class)
    ->name(RouteName::RSS);
