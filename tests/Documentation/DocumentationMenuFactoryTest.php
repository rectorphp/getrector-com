<?php

declare(strict_types=1);

namespace App\Tests\Documentation;

use App\Documentation\DocumentationMenuFactory;
use App\Documentation\DocumentationMenuItem;
use App\Documentation\DocumentationMenuItemFactory;
use Illuminate\Contracts\Routing\UrlGenerator;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;

#[AllowMockObjectsWithoutExpectations]
final class DocumentationMenuFactoryTest extends TestCase
{
    private DocumentationMenuFactory $documentationMenuFactory;

    protected function setUp(): void
    {
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator
            ->method('action')
            ->willReturn('/index.html');

        $this->documentationMenuFactory = new DocumentationMenuFactory(
            new DocumentationMenuItemFactory($urlGenerator),
        );
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
            $this->assertContainsOnlyInstancesOf(DocumentationMenuItem::class, $documentationSections);
        }
    }
}
