<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Tests\Utils;

use Iterator;
use Nette\Utils\FileSystem;
use Rector\Website\Demo\Utils\FileDiffCleaner;
use Rector\Website\GetRectorKernel;
use Symplify\PackageBuilder\Tests\AbstractKernelTestCase;

final class FileDiffCleanerTest extends AbstractKernelTestCase
{
    /**
     * @var FileDiffCleaner
     */
    private $fileDiffCleaner;

    protected function setUp(): void
    {
        $this->bootKernel(GetRectorKernel::class);
        $this->fileDiffCleaner = self::$container->get(FileDiffCleaner::class);
    }

    /**
     * @dataProvider provideData()
     */
    public function test(string $inputFile, string $expectedFile): void
    {
        $inputContent = FileSystem::read($inputFile);
        $cleanedContent = $this->fileDiffCleaner->clean($inputContent);

        $this->assertStringEqualsFile($expectedFile, $cleanedContent);
    }

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
    }
}
