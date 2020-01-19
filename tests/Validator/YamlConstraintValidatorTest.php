<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Validator;

use Rector\Website\GetRectorKernel;
use Rector\Website\ValueObject\DemoFormData;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symplify\PackageBuilder\Tests\AbstractKernelTestCase;

final class YamlConstraintValidatorTest extends AbstractKernelTestCase
{
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
        $demoFormData = new DemoFormData('<?php echo "hi";', $content);
        $constraints = $this->validator->validate($demoFormData);

        $this->assertCount(0, $constraints);
    }

    public function provideDataForTestValidYamlSyntax()
    {
        yield [''];
        yield ['services:'];
    }

    /**
     * @dataProvider provideDataForTestInvalidYamlSyntax()
     */
    public function testInvalidYamlSyntax(string $content): void
    {

        $demoFormData = new DemoFormData('<?php echo "hi";', $content);
        $constraints = $this->validator->validate($demoFormData);

        $this->assertCount(1, $constraints);

        /** @var ConstraintViolation $constraintViolation */
        $constraintViolation = $constraints[0];

        $expectedMessage = sprintf('Value "%s" is not a valid YAML', $content);
        $this->assertSame($expectedMessage, $constraintViolation->getMessage());
    }

    public function provideDataForTestInvalidYamlSyntax()
    {
        yield ['key: value: value2'];
    }
}
