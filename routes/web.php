<?php

declare(strict_types=1);

use App\Ast\Controller\AstController;
use App\Ast\Controller\AstDetailController;
use App\Ast\Controller\ProcessAstFormController;
use App\Controller\AboutController;
use App\Controller\Blog\BlogController;
use App\Controller\Blog\PostController;
use App\Controller\CodebaseRenovationController;
use App\Controller\ContactController;
use App\Controller\Demo\CustomRuleController;
use App\Controller\Demo\CustomRuleDetailController;
use App\Controller\Demo\DemoController;
use App\Controller\Demo\DemoDetailController;
use App\Controller\Demo\ProcessCustomRuleFormController;
use App\Controller\Demo\ProcessDemoFormController;
use App\Controller\DocumentationController;
use App\Controller\FindRuleController;
use App\Controller\HireTeamController;
use App\Controller\HomepageController;
use App\Controller\InteractiveController;
use App\Controller\RssController;
use App\Controller\RuleDetailController;
use App\Controller\Socials\PostThumbnailController;
use App\Controller\Socials\RuleThumbnailController;
use App\Controller\StepByStep\StepByStepController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomepageController::class);

// redirect of old page
Route::redirect('/documentation/rules-overview', '/find-rule');

Route::get('documentation/{section?}', DocumentationController::class);

Route::get('about', AboutController::class);
Route::get('blog', BlogController::class);
Route::get('book', function () {
    return redirect()->to('https://leanpub.com/rector-the-power-of-automated-refactoring');
});

Route::get('contact', ContactController::class);
Route::get('hire-team', HireTeamController::class);

Route::get('play-and-learn', InteractiveController::class);

// redirect of old page
Route::get('project-timeline', static function () {
    return redirect()->action(HireTeamController::class);
});

Route::get('blog/{postSlug}', PostController::class);

Route::get('/thumbnail/{title}.png', PostThumbnailController::class)
     ->where('title', '.*');

Route::get('/rule-thumbnail/{ruleSlug}.png', RuleThumbnailController::class)
    ->where('ruleSlug', '.*');

Route::get('rss.xml', RssController::class);

// 2024 new stuff
Route::get('codebase-renovation', CodebaseRenovationController::class);

Route::get('find-rule', FindRuleController::class);
Route::get('rule-detail/{slug}', RuleDetailController::class);

Route::get('custom-rule/{uuid}', CustomRuleDetailController::class)
    ->whereUuid('uuid');
Route::get('custom-rule', CustomRuleController::class);
Route::post('process-custom-rule', ProcessCustomRuleFormController::class);


// demo
Route::get('demo/{uuid}', DemoDetailController::class)
    ->whereUuid('uuid');
Route::get('demo', DemoController::class);
Route::post('process-demo', ProcessDemoFormController::class);

// ast
Route::get('ast', AstController::class);
Route::get('ast/{hash}', AstDetailController::class);
Route::post('process-ast', ProcessAstFormController::class);

Route::get('step-by-step', StepByStepController::class);
Route::get('stats/find-rule', \App\Controller\Stats\FindRuleStatsController::class);
