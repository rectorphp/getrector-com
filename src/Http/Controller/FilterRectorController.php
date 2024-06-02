<?php

declare(strict_types=1);

namespace Rector\Website\Http\Controller;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

final class FilterRectorController extends Controller
{
    public function __invoke(): View
    {
        return \view('homepage/filter-rector', [
            'page_title' => 'Filter Rector',
        ]);
    }
}
