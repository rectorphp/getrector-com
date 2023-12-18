<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Rector\Website\Http\Controllers\AboutController;
use Rector\Website\Http\Controllers\BlogController;
use Rector\Website\Http\Controllers\BookController;
use Rector\Website\Http\Controllers\ContactController;
use Rector\Website\Http\Controllers\DemoController;
use Rector\Website\Http\Controllers\DemoDetailController;
use Rector\Website\Http\Controllers\DocumentationController;
use Rector\Website\Http\Controllers\ForCompaniesController;
use Rector\Website\Http\Controllers\HomepageController;
use Rector\Website\Http\Controllers\PostController;
use Rector\Website\Http\Controllers\ProcessDemoFormController;
use Rector\Website\Http\Controllers\ProjectTimelineController;
use Rector\Website\Http\Controllers\RssController;
use Rector\Website\Http\Controllers\ThumbnailController;

Route::get('/', HomepageController::class);

Route::get('documentation/{section?}', DocumentationController::class);

Route::get('about', AboutController::class);
Route::get('blog', BlogController::class);
Route::get('book', BookController::class);
Route::get('contact', ContactController::class);
Route::get('hire-team', ForCompaniesController::class);
Route::get('project-timeline', ProjectTimelineController::class);

Route::get('blog/{postSlug}', PostController::class);

Route::get('/thumbnail/{title}.png', ThumbnailController::class)
     ->where('title', '.*');

Route::get('rss.xml', RssController::class);

Route::get('demo/{uuid}', DemoDetailController::class);
Route::get('demo', DemoController::class);

// post routes
Route::post('process-demo', ProcessDemoFormController::class);
