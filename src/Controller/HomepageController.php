<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PostRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

final class HomepageController extends Controller
{
    public function __construct(
        private readonly PostRepository $postRepository,
    ) {
    }

    public function __invoke(): View
    {
        return \view('homepage/homepage', [
            'page_title' => "We'll Speed Up Your Development Process by 300%",
            'recentPosts' => $this->postRepository->fetchLast(5),
            // seo
            'metaTitle' => 'Rector: Fast PHP Code Upgrades & Refactoring',
            'metaDescription' => 'Automate PHP code upgrades and refactoring with Rector. Save time, reduce errors, and modernize your codebase instantly. Try it now!',
        ]);
    }
}
