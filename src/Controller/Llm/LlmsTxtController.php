<?php

declare(strict_types=1);

namespace App\Controller\Llm;

use App\Documentation\DocumentationMenuFactory;
use App\Repository\PostRepository;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

final class LlmsTxtController extends Controller
{
    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly DocumentationMenuFactory $documentationMenuFactory,
    ) {
    }

    public function __invoke(): Response
    {
        return response()->view('llm/llms-txt', [
            'posts' => $this->postRepository->fetchAll(),
            'documentationMenuItemsBySection' => $this->documentationMenuFactory->create(),
        ])->header('Content-Type', 'text/plain');
    }
}
