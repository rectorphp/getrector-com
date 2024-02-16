<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Rector\Website\Http\Controller\AboutController;
use Rector\Website\Http\Controller\Ast\AstController;
use Rector\Website\Http\Controller\Ast\AstDetailController;
use Rector\Website\Http\Controller\Ast\ProcessAstFormController;
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
Route::post('process-demo', ProcessDemoFormController::class);

// ast
// hide on production, until it's finished
if (app()->environment('dev')) {
    Route::get('ast', AstController::class);
    Route::get('ast/{uuid}/{activeNodeId?}', AstDetailController::class);
    Route::post('process-ast', ProcessAstFormController::class);
}
