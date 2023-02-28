<?php

declare(strict_types=1);

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\DemoDetailController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\ForCompaniesController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProcessDemoFormController;
use App\Http\Controllers\RssController;
use App\Http\Controllers\ThumbnailController;
use Illuminate\Support\Facades\Route;
use Rector\Website\Enum\RouteName;

Route::get('/', HomepageController::class)
     ->name(RouteName::HOMEPAGE);

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

Route::get('hire-team', ForCompaniesController::class)
    ->name(RouteName::HIRE_TEAM);

Route::get('blog/{postSlug}', PostController::class)
    ->name(RouteName::POST);

Route::get('/thumbnail/{title}.png', ThumbnailController::class)
     ->name(RouteName::POST_IMAGE)
     ->where('title', '.*');

Route::get('rss.xml', RssController::class)
    ->name(RouteName::RSS);

Route::get('demo/{uuid}', DemoDetailController::class)
     ->name(RouteName::DEMO_DETAIL);

// demo routes
Route::get('demo', DemoController::class)
     ->name(RouteName::DEMO);

// post routes
Route::post('process-demo', ProcessDemoFormController::class)
     ->name(RouteName::PROCESS_DEMO_FORM);
