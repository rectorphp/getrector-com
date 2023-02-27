<?php

use Rector\Website\Controller\DocumentationController;
use Rector\Website\Controller\AboutController;
use Rector\Website\Controller\BlogController;
use Rector\Website\Controller\BookController;
use Rector\Website\Controller\ContactController;
use Rector\Website\Controller\DemoController;
use Rector\Website\Controller\ForCompaniesController;
use Rector\Website\Controller\HomepageController;
use Rector\Website\Controller\PostController;
use Rector\Website\Controller\RssController;
use Illuminate\Support\Facades\Route;

Route::get('documentation/{section}', DocumentationController::class)->name('documentation');

Route::get('documentation/{section}', DocumentationController::class)->name('documentation');

Route::get('documentation/{section}', DocumentationController::class)->name('documentation');

Route::get('documentation/{section}', DocumentationController::class)->name('documentation');

Route::get('about', AboutController::class)->name('about');

Route::get('blog', BlogController::class)->name('blog');

Route::get('book', BookController::class)->name('book');

Route::get('contact', ContactController::class)->name('contact');

Route::get('demo/{uuid}', DemoController::class)->name('demo_detail');

Route::get('demo', DemoController::class)->name('demo');

Route::get('documentation/{section}', DocumentationController::class)->name('documentation');

Route::get('for-companies', ForCompaniesController::class)->name('for_companies');

Route::get('hire-team', ForCompaniesController::class)->name('hire_team');

Route::get('/', HomepageController::class)->name('homepage');

Route::get('blog/{postSlug}', PostController::class)->name('post');

Route::get('rss.xml', RssController::class)->name('rss');
