<?php

\Illuminate\Support\Facades\Route::get('/some', \Rector\Website\Utils\Tests\Rector\Rector\ClassMethod\SymfonyRouteAttributesToLaravelRouteFileRector\Fixture\SomeController::class)->name('some');
