<?php

declare(strict_types=1);

namespace Rector\Website\Blog\Controller;

use Rector\Website\Blog\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PostController extends AbstractController
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @Route(path="blog/{postSlug}", name="post", requirements={"postSlug"=".+"})
     */
    public function __invoke(string $postSlug): Response
    {
        $post = $this->postRepository->findBySlug($postSlug);
        if ($post === null) {
            $message = sprintf("Post with slug '%s' not found", $postSlug);
            throw $this->createNotFoundException($message);
        }

        return $this->render('blog/post.twig', [
            'post' => $post,
        ]);
    }
}
