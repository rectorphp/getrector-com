<?php

declare(strict_types=1);

namespace App\Http\Controller;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Rector\Website\Documentation\DocumentationMenuFactory;
use Rector\Website\Documentation\HTMLFromMarkdownFactory;
use Symfony\Component\Routing\Annotation\Route;

final class DocumentationController extends Controller
{
    public function __construct(
        private readonly HTMLFromMarkdownFactory $htmlFromMarkdownFactory,
        private readonly DocumentationMenuFactory $documentationMenuFactory
    ) {
    }

    //#[Route(path: 'documentation/{section}', name: 'documentation', defaults: [
    //    // if the section is empty, default to "introduction"
    //    'section' => 'introduction',
    //])]
    public function __invoke(string $section = 'introduction'): View
    {
        $sectionHtmlContents = $this->htmlFromMarkdownFactory->create(
            __DIR__ . '/../../../resources/docs/' . $section . '.md'
        );

        return \view('docs/section', [
            'section_title' => $this->documentationMenuFactory->createSectionTitle($section),
            'section_html_contents' => $sectionHtmlContents,
            'documentations_sections_by_category' => $this->documentationMenuFactory->create(),
        ]);
    }
}
