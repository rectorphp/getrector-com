<?php

declare(strict_types=1);

namespace App\Controller;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

final class FilterRectorController extends Controller
{
    public function __invoke(): View
    {
        return \view('homepage/filter-rector', [
            'page_title' => 'Find the best Rule',
        ]);
    }
}
