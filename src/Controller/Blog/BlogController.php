<?php

declare(strict_types=1);

namespace App\Controller\Blog;

use App\Repository\PostRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

final class BlogController extends Controller
{
    public function __invoke(PostRepository $postRepository): View
    {
        return \view('blog/blog', [
            'page_title' => 'Learn about Rector, Upgrades and Planning',
            'posts' => $postRepository->getPosts(),
            // seo
            'metaTitle' => 'Rector Blog: PHP Code Tips & Updates',
            'metaDescription' => 'Read the latest PHP refactoring tips, Rector updates, and automation guides. Level up your coding skills with our expert blog posts.',
        ]);
    }
}
