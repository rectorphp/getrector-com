<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;
use Rector\Website\Documentation\DocumentationMenuFactory;
use Rector\Website\ValueObject\DocumentationSection;

test('section title', function () {
    $documentationMenuFactory = new DocumentationMenuFactory();

    $sectionTitle = $documentationMenuFactory->createSectionTitle('run-in-cI');
    expect($sectionTitle)
        ->toBe('Run in CI');
});

test('create', function () {
    $documentationMenuFactory = new DocumentationMenuFactory();

    $documentationSectionsByCategory = $documentationMenuFactory->create();

    foreach ($documentationSectionsByCategory as $category => $documentationSections) {
        expect($category)->toBeString();
        Assert::assertContainsOnlyInstancesOf(DocumentationSection::class, $documentationSections);
    }
});
