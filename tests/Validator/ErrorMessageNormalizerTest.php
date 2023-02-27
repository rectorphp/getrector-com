<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Validator;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Website\Tests\AbstractTestCase;
use Rector\Website\Utils\ErrorMessageNormalizer;

final class ErrorMessageNormalizerTest extends AbstractTestCase
{
    private ErrorMessageNormalizer $errorMessageNormalizer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->errorMessageNormalizer = $this->make(ErrorMessageNormalizer::class);
    }

    #[DataProvider('provideDataForTest')]
    public function test(string $errorMessage, string $expectedNormalizedMessage): void
    {
        $normalizedMessage = $this->errorMessageNormalizer->normalize($errorMessage);
        $this->assertSame($expectedNormalizedMessage, $normalizedMessage);
    }

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
