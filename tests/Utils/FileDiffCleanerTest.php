<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Utils;

use Iterator;
use Nette\Utils\FileSystem;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rector\Website\Utils\FileDiffCleaner;

final class FileDiffCleanerTest extends TestCase
{
    private FileDiffCleaner $fileDiffCleaner;

    protected function setUp(): void
    {
        $this->fileDiffCleaner = new FileDiffCleaner();
    }

    #[DataProvider('provideData')]
    public function test(string $fixtureFilePath): void
    {
        $fixtureFileContents = FileSystem::read($fixtureFilePath);
        [$inputRawContents, $expectedCleanContents] = $this->split($fixtureFileContents);

        $cleanedContent = $this->fileDiffCleaner->clean($inputRawContents);
        $cleanedContent = trim($cleanedContent);

        $expectedCleanContents = trim((string) $expectedCleanContents);

        $this->assertSame($expectedCleanContents, $cleanedContent);
    }

    public static function provideData(): Iterator
    {
        foreach ((array) glob(__DIR__ . '/Fixture/*.txt') as $filePath) {
            yield [$filePath];
        }
    }

    /**
     * @return array{string, string}
     */
    private function split(string $fileContents): array
    {
        $parts = str($fileContents)
            ->split('#^\-\-\-\-\-\n#m');
        return [$parts[0], $parts[1]];
    }
}
