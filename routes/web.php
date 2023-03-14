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

Route::get('/', HomepageController::class);

Route::get('documentation/{section?}', DocumentationController::class);

Route::get('about', AboutController::class);
Route::get('blog', BlogController::class);
Route::get('book', BookController::class);
Route::get('contact', ContactController::class);
Route::get('hire-team', ForCompaniesController::class);

Route::get('blog/{postSlug}', PostController::class);

Route::get('/thumbnail/{title}.png', ThumbnailController::class)
     ->where('title', '.*');

Route::get('rss.xml', RssController::class);

Route::get('demo/{uuid}', DemoDetailController::class);
Route::get('demo', DemoController::class);

// post routes
Route::post('process-demo', ProcessDemoFormController::class);
