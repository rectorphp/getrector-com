<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Validator;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Website\GetRectorKernel;
use Rector\Website\Utils\ErrorMessageNormalizer;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

/**
 * @see \Rector\Website\Utils\ErrorMessageNormalizer
 */
final class ErrorMessageNormalizerTest extends AbstractKernelTestCase
{
    private ErrorMessageNormalizer $errorMessageNormalizer;

    protected function setUp(): void
    {
        $this->bootKernel(GetRectorKernel::class);
        $this->errorMessageNormalizer = self::$container->get(ErrorMessageNormalizer::class);
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
