<?php

declare(strict_types=1);

namespace Rector\Website\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

final class ForCompaniesController extends Controller
{
    public function __invoke(): View
    {
        return \view('homepage/hire_team', [
            'page_title' => 'Hire the Rector Team to Reduce Costs and Technical Debt',
        ]);
    }
}
