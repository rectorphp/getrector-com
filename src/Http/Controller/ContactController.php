<?php

declare(strict_types=1);

namespace Rector\Website\Http\Controller;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

final class ContactController extends Controller
{
    public function __invoke(): View
    {
        return \view('homepage/contact', [
            'page_title' => 'Reach Us',
        ]);
    }
}
