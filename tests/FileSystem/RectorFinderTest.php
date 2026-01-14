<?php

declare(strict_types=1);

namespace App\Tests\FileSystem;

use App\FileSystem\RectorFinder;
use App\Sets\RectorSetsTreeProvider;
use PHPUnit\Framework\TestCase;

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

        $this->assertSame(
            count($uniqueShortNames),
            count($uniqueLongNames),
            'There are no duplicated short class names.'
        );
    }
}
