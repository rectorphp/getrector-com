<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\UnicodeString;
use Webmozart\Assert\Assert;

final class DocsSectionController extends AbstractController
{
    public function __construct(
        private readonly \ParsedownExtra $parsedownExtra
    ) {
    }

    #[Route(path: 'docs/{section}', name: RouteName::DOCS_SECTION)]
    public function __invoke(string $section): Response
    {
        $sectionMarkdownFilePath = __DIR__ . '/../../data/docs/' . $section . '.md';
        $sectionHtmlContents = $this->createHtmlSectionContents($sectionMarkdownFilePath);

        // render menu ;)



        return $this->render('docs/section.twig', [
            'section_title' => $this->createSectionTitle($section),
            'section_html_contents' => $sectionHtmlContents,
        ]);
    }

    private function createHtmlSectionContents(string $markdownFilePath): string
    {
        Assert::fileExists($markdownFilePath);
        $sectionFileContents = FileSystem::read($markdownFilePath);

        return $this->parsedownExtra->parse($sectionFileContents);
    }

    private function createSectionTitle(string $section): string
    {
        $sectionWords = str_replace('-', ' ', $section);
        return ucwords($sectionWords);
    }
}
