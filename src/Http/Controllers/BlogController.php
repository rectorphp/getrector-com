<?php

declare(strict_types=1);

namespace Rector\Website\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Rector\Website\Repository\PostRepository;

final class BlogController extends Controller
{
    public function __construct(
        private readonly PostRepository $postRepository,
    ) {
    }

    public function __invoke(): View
    {
        return \view('blog/blog', [
            'page_title' => 'Read about Rector',
            'posts' => $this->postRepository->getPosts(),
        ]);
    }
}
