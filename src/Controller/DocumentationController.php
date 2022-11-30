<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Rector\Website\Documentation\DocumentationMenuFactory;
use Rector\Website\Documentation\HTMLFromMarkdownFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DocumentationController extends AbstractController
{
    public function __construct(
        private readonly HTMLFromMarkdownFactory $htmlFromMarkdownFactory,
        private readonly DocumentationMenuFactory $documentationMenuFactory
    ) {
    }

    #[Route(path: 'docs/{section}', name: 'documentation', defaults: [
        'section' => 'introduction',
    ])]
    public function __invoke(string $section): Response
    {
        $sectionMarkdownFilePath = __DIR__ . '/../../data/docs/' . $section . '.md';
        $sectionHtmlContents = $this->htmlFromMarkdownFactory->create($sectionMarkdownFilePath);

        // render menu ;)
        return $this->render('docs/section.twig', [
            'section_title' => $this->documentationMenuFactory->createSectionTitle($section),
            'section_html_contents' => $sectionHtmlContents,
            'documentations_sections' => $this->documentationMenuFactory->create(),
        ]);
    }
}
