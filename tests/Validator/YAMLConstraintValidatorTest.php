<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Validator;

use Iterator;
use Rector\Website\GetRectorKernel;
use Rector\Website\ValueObject\DemoFormData;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symplify\PackageBuilder\Tests\AbstractKernelTestCase;

/**
 * @see \Rector\Website\Validator\YAMLConstraintValidator
 */
final class YAMLConstraintValidatorTest extends AbstractKernelTestCase
{
    /**
     * @var string
     */
    private const VALID_PHP = '<?php echo "hi";';

    /**
     * @var ValidatorInterface
     */
    private $validator;

    protected function setUp(): void
    {
        self::bootKernel(GetRectorKernel::class);
        $this->validator = self::$container->get(ValidatorInterface::class);
    }

    /**
     * @dataProvider provideDataForTestValidYamlSyntax()
     */
    public function testValidYamlSyntax(string $content): void
    {
        $demoFormData = new DemoFormData(self::VALID_PHP, $content);
        $constraints = $this->validator->validate($demoFormData);
        $this->assertCount(0, $constraints);
    }

    public function provideDataForTestValidYamlSyntax(): Iterator
    {
        yield [''];
        yield ['services:'];
    }

    /**
     * @dataProvider provideDataForTestInvalidYamlSyntax()
     */
    public function testInvalidYamlSyntax(string $content, int $expetedLine): void
    {
        $demoFormData = new DemoFormData(self::VALID_PHP, $content);
        $constraints = $this->validator->validate($demoFormData);

        $this->assertCount(1, $constraints);

        /** @var ConstraintViolation $constraintViolation */
        $constraintViolation = $constraints[0];

        $expectedMessageFormat = 'Fix YAML syntax: %s at line ' . $expetedLine . ' %s';

        /** @see https://phpunit.readthedocs.io/en/8.5/assertions.html#assertstringmatchesformat */
        $this->assertStringMatchesFormat($expectedMessageFormat, $constraintViolation->getMessage());
    }

    public function provideDataForTestInvalidYamlSyntax(): Iterator
    {
        yield ['key: value: value2', 1];
        yield ["key: value\nvalue2: key: key", 2];
    }
}
