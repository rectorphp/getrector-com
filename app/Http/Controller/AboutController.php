<?php

declare(strict_types=1);

namespace App\Http\Controller;

final class AboutController extends \Illuminate\Routing\Controller
{
    public function __invoke(): \Illuminate\Contracts\View\View
    {
        return \view('homepage/about', [
            'page_title' => 'About Rector',
        ]);
    }
}
