<?php

declare(strict_types=1);

namespace App\Tests\FileSystem;

use App\FileSystem\RectorFinder;
use App\Sets\RectorSetsTreeProvider;
use PHPUnit\Framework\TestCase;
use Rector\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector;

final class RectorFinderTest extends TestCase
{
    private RectorFinder $rectorFinder;

    protected function setUp(): void
    {
        $this->rectorFinder = new RectorFinder(new RectorSetsTreeProvider());
    }

    public function testFindDuplicated(): void
    {
        $foundRectors = $this->rectorFinder->find();

        $shortNames = [];
        $longNames = [];
        foreach ($foundRectors as $ruleMetadata) {
            $shortNames[] = $ruleMetadata->getRuleShortClass();
            $longNames[] = $ruleMetadata->getRectorClass();
        }

        $uniqueShortNames = array_unique($shortNames);
        $uniqueLongNames = array_unique($longNames);

        $this->assertNotSame(
            count($uniqueShortNames),
            count($uniqueLongNames),
            'There are duplicated short class names.'
        );

        // get duplicated short names and report different slug
        $duplicatedShortNames = array_diff_key($shortNames, $uniqueShortNames);

        $this->assertContains(
            'DeprecatedAnnotationToDeprecatedAttributeRector',
            $duplicatedShortNames,
            'Expected DeprecatedAnnotationToDeprecatedAttributeRector to be one of the duplicated short names.'
        );

        foreach ($foundRectors as $foundRector) {
            if ($foundRector->getRectorClass() === DeprecatedAnnotationToDeprecatedAttributeRector::class) {
                $this->assertSame(
                    'php-php-84-deprecated-annotation-to-deprecated-attribute-rector',
                    $foundRector->getSlug()
                );
            }

            if ($foundRector->getRectorClass() === \Rector\Php85\Rector\Const_\DeprecatedAnnotationToDeprecatedAttributeRector::class) {
                $this->assertSame(
                    'php-php-85-deprecated-annotation-to-deprecated-attribute-rector',
                    $foundRector->getSlug()
                );
            }
        }
    }
}
