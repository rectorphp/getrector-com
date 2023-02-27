<?php

declare(strict_types=1);

namespace App\Http\Controller;

final class BookController extends \Illuminate\Routing\Controller
{
    public function __invoke(): \Illuminate\Contracts\View\View
    {
        return \view('homepage/book', [
            'page_title' => 'The Power of Automated&nbsp;Refactoring',
        ]);
    }
}
