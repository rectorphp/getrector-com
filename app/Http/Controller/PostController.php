<?php

declare(strict_types=1);

namespace App\Http\Controller;

use Rector\Website\Entity\Post;
use Rector\Website\Repository\PostRepository;
use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class PostController extends \Illuminate\Routing\Controller
{
    public function __construct(
        private readonly PostRepository $postRepository,
    ) {
    }

    //#[Route(path: 'blog/{postSlug}', name: RouteName::POST, requirements: [
    //    'postSlug' => '(\d+\/\d+.+|[\w\-]+)',
    //])]
    public function __invoke(string $postSlug): Response
    {
        $post = $this->postRepository->findBySlug($postSlug);
        if (! $post instanceof Post) {
            $message = sprintf("Post with slug '%s' not found", $postSlug);
            throw new NotFoundHttpException($message);
        }

        return \view('blog/post.twig', [
            'post' => $post,
        ]);
    }
}
