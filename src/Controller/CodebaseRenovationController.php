<?php

declare(strict_types=1);

namespace App\Controller;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

final class CodebaseRenovationController extends Controller
{
    public function __invoke(): View
    {
        return \view('homepage/codebase-renovation', [
            'page_title' => 'Codebase Renovation',
        ]);
    }
}
