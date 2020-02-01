<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Validator;

use Iterator;
use Rector\Website\Error\ErrorMessageNormalizer;
use Rector\Website\GetRectorKernel;
use Symplify\PackageBuilder\Tests\AbstractKernelTestCase;

/**
 * @see \Rector\Website\Error\ErrorMessageNormalizer
 */
final class ErrorMessageNormalizerTest extends AbstractKernelTestCase
{
    /**
     * @var ErrorMessageNormalizer
     */
    private $errorMessageNormalizer;

    protected function setUp(): void
    {
        $this->bootKernel(GetRectorKernel::class);
        $this->errorMessageNormalizer = self::$container->get(ErrorMessageNormalizer::class);
    }

    /**
     * @dataProvider provideDataForTest()
     */
    public function test(string $errorMessage, string $expectedNormalizedMessage): void
    {
        $normalizedMessage = $this->errorMessageNormalizer->normalize($errorMessage);
        $this->assertSame($expectedNormalizedMessage, $normalizedMessage);
    }

    public function provideDataForTest(): Iterator
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
