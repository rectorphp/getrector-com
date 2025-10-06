<?php

declare(strict_types=1);

namespace App\Tests\Validator;

use App\Tests\AbstractTestCase;
use App\Utils\ErrorMessageNormalizer;
use Iterator;
use Override;
use PHPUnit\Framework\Attributes\DataProvider;

final class ErrorMessageNormalizerTest extends AbstractTestCase
{
    private ErrorMessageNormalizer $errorMessageNormalizer;

    #[Override]
    protected function setUp(): void
    {
        $this->errorMessageNormalizer = $this->make(ErrorMessageNormalizer::class);
    }

    #[DataProvider('provideDataForTest')]
    public function test(string $errorMessage, string $expectedNormalizedMessage): void
    {
        $normalizedMessage = $this->errorMessageNormalizer->normalize($errorMessage);
        $this->assertSame($expectedNormalizedMessage, $normalizedMessage);
    }

    /**
     * @return Iterator<array<int, string>>
     */
    public static function provideDataForTest(): Iterator
    {
        yield ['message', 'message'];
        yield [
            "Class 'ClassIsMissing' not found in /project/rector_analyzed_file.php",
            'Class "ClassIsMissing" is missing. Complete it to code input, e.g. "class ClassIsMissing {}"',
        ];
        yield [
            "Interface 'InterfaceIsMissing' not found in /project/rector_analyzed_file.php",
            'Interface "InterfaceIsMissing" is missing. Complete it to code input, e.g. "interface InterfaceIsMissing {}"',
        ];
    }
}
