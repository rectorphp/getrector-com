<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Rector\Website\Controller\AboutController;
use Rector\Website\Controller\Ast\AstController;
use Rector\Website\Controller\Ast\AstDetailController;
use Rector\Website\Controller\Ast\ProcessAstFormController;
use Rector\Website\Controller\Blog\BlogController;
use Rector\Website\Controller\Blog\PostController;
use Rector\Website\Controller\BookController;
use Rector\Website\Controller\ContactController;
use Rector\Website\Controller\Demo\DemoController;
use Rector\Website\Controller\Demo\DemoDetailController;
use Rector\Website\Controller\Demo\ProcessDemoFormController;
use Rector\Website\Controller\DocumentationController;
use Rector\Website\Controller\FilterRectorController;
use Rector\Website\Controller\HireTeamController;
use Rector\Website\Controller\HomepageController;
use Rector\Website\Controller\RssController;
use Rector\Website\Controller\ThumbnailController;

Route::get('/', HomepageController::class);
Route::get('documentation/{section?}', DocumentationController::class);

Route::get('about', AboutController::class);
Route::get('blog', BlogController::class);
Route::get('book', BookController::class);
Route::get('contact', ContactController::class);
Route::get('hire-team', HireTeamController::class);

// redirect of old page
Route::get('project-timeline', static function () {
    return redirect()->action(HireTeamController::class);
});

Route::get('blog/{postSlug}', PostController::class);

Route::get('/thumbnail/{title}.png', ThumbnailController::class)
     ->where('title', '.*');

Route::get('rss.xml', RssController::class);
Route::get('filter', FilterRectorController::class);

// demo
Route::get('demo/{uuid}', DemoDetailController::class)
    ->whereUuid('uuid');
Route::get('demo', DemoController::class);
Route::post('process-demo', ProcessDemoFormController::class);

// ast
Route::get('ast', AstController::class);
Route::get('ast/{uuid}/{activeNodeId?}', AstDetailController::class)
    ->whereUuid('uuid')
    ->where('activeNodeId', '\d+');
Route::post('process-ast', ProcessAstFormController::class);
