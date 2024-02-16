<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use Rector\Website\Http\Controller\AboutController;
use Rector\Website\Http\Controller\Blog\BlogController;
use Rector\Website\Http\Controller\Blog\PostController;
use Rector\Website\Http\Controller\BookController;
use Rector\Website\Http\Controller\ContactController;
use Rector\Website\Http\Controller\Demo\DemoController;
use Rector\Website\Http\Controller\Demo\DemoDetailController;
use Rector\Website\Http\Controller\Demo\ProcessDemoFormController;
use Rector\Website\Http\Controller\DocumentationController;
use Rector\Website\Http\Controller\ForCompaniesController;
use Rector\Website\Http\Controller\HomepageController;
use Rector\Website\Http\Controller\ProjectTimelineController;
use Rector\Website\Http\Controller\RssController;
use Rector\Website\Http\Controller\ThumbnailController;
=======
use Rector\Website\Http\Controllers\AboutController;
use Rector\Website\Http\Controllers\AstController;
use Rector\Website\Http\Controllers\BlogController;
use Rector\Website\Http\Controllers\BookController;
use Rector\Website\Http\Controllers\ContactController;
use Rector\Website\Http\Controllers\DemoController;
use Rector\Website\Http\Controllers\DemoDetailController;
use Rector\Website\Http\Controllers\DocumentationController;
use Rector\Website\Http\Controllers\ForCompaniesController;
use Rector\Website\Http\Controllers\HomepageController;
use Rector\Website\Http\Controllers\PostController;
use Rector\Website\Http\Controllers\ProcessAstFormController;
use Rector\Website\Http\Controllers\ProcessDemoFormController;
use Rector\Website\Http\Controllers\ProjectTimelineController;
use Rector\Website\Http\Controllers\RssController;
use Rector\Website\Http\Controllers\ThumbnailController;
>>>>>>> 2bcd232 (kick of)

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

// demo
Route::get('demo/{uuid}', DemoDetailController::class);
Route::get('demo', DemoController::class);

// ast
Route::get('ast', AstController::class);

// post routes
Route::post('process-demo', ProcessDemoFormController::class);
Route::post('process-ast', ProcessAstFormController::class);
