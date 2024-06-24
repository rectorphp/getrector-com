<?php

declare(strict_types=1);
use Rector\Website\Controller\Demo\CustomRuleDetailController;

use Illuminate\Support\Facades\Route;
use Rector\Website\Ast\Controller\AstController;
use Rector\Website\Ast\Controller\AstDetailController;
use Rector\Website\Ast\Controller\ProcessAstFormController;
use Rector\Website\Controller\AboutController;
use Rector\Website\Controller\Blog\BlogController;
use Rector\Website\Controller\Blog\PostController;
use Rector\Website\Controller\BookController;
use Rector\Website\Controller\ContactController;
use Rector\Website\Controller\Demo\CustomRuleController;
use Rector\Website\Controller\Demo\DemoController;
use Rector\Website\Controller\Demo\DemoDetailController;
use Rector\Website\Controller\Demo\ProcessCustomRuleFormController;
use Rector\Website\Controller\Demo\ProcessDemoFormController;
use Rector\Website\Controller\DocumentationController;
use Rector\Website\Controller\FilterRectorController;
use Rector\Website\Controller\HireTeamController;
use Rector\Website\Controller\HomepageController;
use Rector\Website\Controller\InteractiveController;
use Rector\Website\Controller\RssController;
use Rector\Website\Controller\ThumbnailController;

Route::get('/', HomepageController::class);
Route::get('documentation/{section?}', DocumentationController::class);

Route::get('about', AboutController::class);
Route::get('blog', BlogController::class);
Route::get('book', BookController::class);
Route::get('contact', ContactController::class);
Route::get('hire-team', HireTeamController::class);

Route::get('play-and-learn', InteractiveController::class);

// redirect of old page
Route::get('project-timeline', static function () {
    return redirect()->action(HireTeamController::class);
});

Route::get('blog/{postSlug}', PostController::class);

Route::get('/thumbnail/{title}.png', ThumbnailController::class)
     ->where('title', '.*');

Route::get('rss.xml', RssController::class);

// on dev for now only
Route::get('find-rule', FilterRectorController::class);

// demo
Route::get('demo/{uuid}', DemoDetailController::class)
    ->whereUuid('uuid');
Route::get('demo', DemoController::class);
Route::post('process-demo', ProcessDemoFormController::class);

// ast
Route::get('ast', AstController::class);
Route::get('ast/{hash}', AstDetailController::class);
Route::post('process-ast', ProcessAstFormController::class);

// in development only
if (app('env') === 'dev') {
    Route::get('custom-rule/{uuid}', CustomRuleDetailController::class)
        ->whereUuid('uuid');
    Route::get('custom-rule', CustomRuleController::class);
    Route::post('process-custom-rule', ProcessCustomRuleFormController::class);
}
