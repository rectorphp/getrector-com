<?php

declare(strict_types=1);

namespace App\Http\Controller;

use Illuminate\Contracts\View\View;
use Rector\Website\Repository\PostRepository;

final class BlogController extends \Illuminate\Routing\Controller
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
