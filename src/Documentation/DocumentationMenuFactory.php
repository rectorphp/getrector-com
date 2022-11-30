<?php

declare(strict_types=1);

namespace Rector\Website\Documentation;

use Rector\Website\ValueObject\DocumentationSection;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @see \Rector\Website\Tests\Documentation\DocumentationMenuFactoryTest
 */
final class DocumentationMenuFactory
{
    /**
     * @return DocumentationSection[]
     */
    public function create(): array
    {
        $fileInfos = $this->findMarkdownFileInfos(__DIR__ . '/../../data/docs');

        $documentationSection = [];

        foreach ($fileInfos as $fileInfo) {
            $basename = $fileInfo->getBasename('.md');

            if ($basename === 'introduction') {
                continue;
            }

            $sectionTitle = $this->createSectionTitle($basename);

            $documentationSection[] = new DocumentationSection($basename, $sectionTitle);
        }

        return $documentationSection;
    }

    public function createSectionTitle(string $section): string
    {
        $sectionWords = str_replace('-', ' ', $section);
        $sectionWords = ucwords($sectionWords);

        return str_replace([' In', 'Ci'], [' in', 'CI'], $sectionWords);
    }

    /**
     * @return SplFileInfo[]
     */
    private function findMarkdownFileInfos(string $directory): array
    {
        $finder = new Finder();
        $finder->in($directory)
            ->files()
            ->name('*.md');

        return iterator_to_array($finder);
    }
}
