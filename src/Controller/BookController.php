<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

final class BookController extends Controller
{
    public function __invoke(): View
    {
        return \view('homepage/book', [
            'page_title' => 'The Power of Automated&nbsp;Refactoring',
        ]);
    }
}
