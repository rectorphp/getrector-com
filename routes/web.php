<?php

use App\Http\Controller\DocumentationController;
use App\Http\Controller\AboutController;
use App\Http\Controller\BlogController;
use App\Http\Controller\BookController;
use App\Http\Controller\ContactController;
use App\Http\Controller\DemoController;
use App\Http\Controller\ForCompaniesController;
use App\Http\Controller\HomepageController;
use App\Http\Controller\PostController;
use App\Http\Controller\RssController;
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
