<?php

declare(strict_types=1);

namespace Rector\Website\Documentation;

use Rector\Website\ValueObject\DocumentationSection;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

final class DocumentationMenuFactory
{
    /**
     * @return DocumentationSection[]
     */
    public function create(): array
    {
        $finder = new Finder();
        $finder->in(__DIR__ . '/../../data/docs')
            ->files()
            ->name('*.md');

        /** @var SplFileInfo[] $fileInfos */
        $fileInfos = iterator_to_array($finder);

        $documentationSection = [];

        foreach ($fileInfos as $fileInfo) {
            $basename = $fileInfo->getBasename('.md');
            $sectionTitle = $this->createSectionTitle($basename);

            $documentationSection[] = new DocumentationSection($basename, $sectionTitle);
        }

        return $documentationSection;
    }

    private function createSectionTitle(string $section): string
    {
        $sectionWords = str_replace('-', ' ', $section);
        return ucwords($sectionWords);
    }
}
