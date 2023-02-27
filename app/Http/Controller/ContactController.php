<?php

declare(strict_types=1);

namespace App\Http\Controller;

final class ContactController extends \Illuminate\Routing\Controller
{
    public function __invoke(): \Illuminate\Contracts\View\View
    {
        return \view('homepage/contact', [
            'page_title' => 'Reach Us',
        ]);
    }
}
