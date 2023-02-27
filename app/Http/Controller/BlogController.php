<?php

declare(strict_types=1);

namespace App\Http\Controller;

use Rector\Website\Repository\PostRepository;
use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BlogController extends \Illuminate\Routing\Controller
{
    public function __construct(
        private readonly PostRepository $postRepository,
    ) {
    }

    public function __invoke(): \Illuminate\Contracts\View\View
    {
        return \view('blog/blog', [
            'posts' => $this->postRepository->getPosts(),
        ]);
    }
}
