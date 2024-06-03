<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Rector\Website\Repository\PostRepository;

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
            'last_5_posts' => $this->postRepository->fetchLast(5),
        ]);
    }
}
