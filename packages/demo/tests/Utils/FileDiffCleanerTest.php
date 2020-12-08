<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Tests\Utils;

use Iterator;
use Rector\Website\Demo\Utils\FileDiffCleaner;
use Rector\Website\GetRectorKernel;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
use Symplify\SmartFileSystem\SmartFileSystem;

final class FileDiffCleanerTest extends AbstractKernelTestCase
{
    private FileDiffCleaner $fileDiffCleaner;

    private SmartFileSystem $smartFileSystem;

    protected function setUp(): void
    {
        $this->bootKernel(GetRectorKernel::class);
        $this->fileDiffCleaner = self::$container->get(FileDiffCleaner::class);
        $this->smartFileSystem = self::$container->get(SmartFileSystem::class);
    }

    /**
     * @dataProvider provideData()
     */
    public function test(string $inputFile, string $expectedFile): void
    {
        $inputContent = $this->smartFileSystem->readFile($inputFile);
        $cleanedContent = $this->fileDiffCleaner->clean($inputContent);

        $this->assertStringEqualsFile($expectedFile, $cleanedContent);
    }

    /**
     * @return Iterator<mixed>
     */
    public function provideData(): Iterator
    {
        yield [
            __DIR__ . '/FileDiffCleanerSource/start_input.txt',
            __DIR__ . '/FileDiffCleanerSource/expected_output.txt',
        ];

        yield [
            __DIR__ . '/FileDiffCleanerSource/newline_input.txt',
            __DIR__ . '/FileDiffCleanerSource/expected_output.txt',
        ];

        yield [
            __DIR__ . '/FileDiffCleanerSource/endline_input.txt',
            __DIR__ . '/FileDiffCleanerSource/endline_output.txt',
        ];
    }
}
