<?php

declare(strict_types=1);

namespace App\Controller;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

final class InteractiveController extends Controller
{
    public function __invoke(): View
    {
        return \view('homepage/interactive', [
            'page_title' => 'Play and Learn',
        ]);
    }
}
