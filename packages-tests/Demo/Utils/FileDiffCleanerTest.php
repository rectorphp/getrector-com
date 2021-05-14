<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Demo\Utils;

use Iterator;
use Rector\Website\Demo\Utils\FileDiffCleaner;
use Rector\Website\GetRectorKernel;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\EasyTesting\StaticFixtureSplitter;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class FileDiffCleanerTest extends AbstractKernelTestCase
{
    private FileDiffCleaner $fileDiffCleaner;

    protected function setUp(): void
    {
        $this->bootKernel(GetRectorKernel::class);
        $this->fileDiffCleaner = self::$container->get(FileDiffCleaner::class);
    }

    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $smartFileInfo): void
    {
        $inputAndExpected = StaticFixtureSplitter::splitFileInfoToInputAndExpected($smartFileInfo);
        $inputContent = $inputAndExpected->getInput();
        $expectedContent = $inputAndExpected->getExpected();

        $cleanedContent = $this->fileDiffCleaner->clean($inputContent);

        $cleanedContent = trim($cleanedContent);

        $expectedContent = trim($expectedContent);

        $this->assertSame($expectedContent, $cleanedContent);
    }

    /**
     * @return Iterator<SmartFileInfo[]>
     */
    public function provideData(): Iterator
    {
        return StaticFixtureFinder::yieldDirectory(__DIR__ . '/Fixture', '*.txt');
    }
}
