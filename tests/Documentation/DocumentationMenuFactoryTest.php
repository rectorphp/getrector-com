<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Documentation;

use PHPUnit\Framework\TestCase;
use Rector\Website\Documentation\DocumentationMenuFactory;
use Rector\Website\ValueObject\DocumentationSection;

final class DocumentationMenuFactoryTest extends TestCase
{
    private DocumentationMenuFactory $documentationMenuFactory;

    protected function setUp(): void
    {
        $this->documentationMenuFactory = new DocumentationMenuFactory();
    }

    public function testSectionTitle(): void
    {
        $sectionTitle = $this->documentationMenuFactory->createSectionTitle('run-in-cI');
        $this->assertSame('Run in CI', $sectionTitle);
    }

    public function testCreate(): void
    {
        $documentationSectionsByCategory = $this->documentationMenuFactory->create();

        foreach ($documentationSectionsByCategory as $category => $documentationSections) {
            $this->assertIsString($category);
            $this->assertContainsOnlyInstancesOf(DocumentationSection::class, $documentationSections);
        }
    }
}
