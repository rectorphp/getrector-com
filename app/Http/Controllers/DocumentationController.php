<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Nette\Utils\FileSystem;
use Rector\Website\Documentation\DocumentationMenuFactory;

final class DocumentationController extends Controller
{
    public function __construct(
        private readonly DocumentationMenuFactory $documentationMenuFactory
    ) {
    }

    public function __invoke(string $section = 'introduction'): View
    {
        $markdownContents = FileSystem::read(__DIR__ . '/../../../resources/docs/' . $section . '.md');

        return \view('docs/section', [
            'section_title' => $this->documentationMenuFactory->createSectionTitle($section),
            'section_markdown_contents' => $markdownContents,
            'documentations_sections_by_category' => $this->documentationMenuFactory->create(),
        ]);
    }
}
