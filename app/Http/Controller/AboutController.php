<?php

declare(strict_types=1);

namespace App\Http\Controller;

use Illuminate\Routing\Controller;
use Illuminate\Contracts\View\View;
final class AboutController extends Controller
{
    public function __invoke(): View
    {
        return \view('homepage/about', [
            'page_title' => 'About Rector',
        ]);
    }
}
