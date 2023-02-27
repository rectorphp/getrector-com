<?php

declare(strict_types=1);

namespace App\Http\Controller;

use Illuminate\Contracts\View\View;
use Rector\Website\Repository\PostRepository;
use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomepageController extends \Illuminate\Routing\Controller
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
