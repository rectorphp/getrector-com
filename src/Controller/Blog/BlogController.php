<?php

declare(strict_types=1);

namespace App\Controller\Blog;

use App\Repository\PostRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

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
