<?php

declare(strict_types=1);

namespace App\Tests\Documentation;

use App\Documentation\DocumentationMenuFactory;
use App\ValueObject\DocumentationSection;
use PHPUnit\Framework\TestCase;

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

    public function testCategory(): void
    {
        $documentationSectionsByCategory = $this->documentationMenuFactory->create();

        foreach ($documentationSectionsByCategory as $category => $documentationSections) {
            $this->assertIsString($category);
            $this->assertContainsOnlyInstancesOf(DocumentationSection::class, $documentationSections);
        }
    }
}
