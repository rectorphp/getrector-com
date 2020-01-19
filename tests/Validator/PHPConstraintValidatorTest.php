<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Validator;

use Iterator;
use Rector\Website\GetRectorKernel;
use Rector\Website\ValueObject\DemoFormData;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symplify\PackageBuilder\Tests\AbstractKernelTestCase;

final class PHPConstraintValidatorTest extends AbstractKernelTestCase
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
     * @dataProvider provideDataForTestValidPHPSyntax()
     */
    public function testValidPHPSyntax(string $content): void
    {
        $demoFormData = new DemoFormData($content, '');
        $constraints = $this->validator->validate($demoFormData);

        $this->assertCount(0, $constraints);
    }

    public function provideDataForTestValidPHPSyntax(): Iterator
    {
        yield ['<?php echo "hi";'];
        yield [' <?php echo "hi";'];
    }

    /**
     * @dataProvider provideDataForTestMissingPHPOpeningTag()
     */
    public function testMissingPHPOpeningTag(string $content): void
    {
        $demoFormData = new DemoFormData($content, '');
        $constraints = $this->validator->validate($demoFormData);

        $this->assertCount(1, $constraints);

        /** @var ConstraintViolation $constraintViolation */
        $constraintViolation = $constraints[0];

        $this->assertSame('Add opening "<?php" tag', $constraintViolation->getMessage());
    }

    public function provideDataForTestMissingPHPOpeningTag(): Iterator
    {
        yield ['php echo'];
        yield ['echo'];
    }

    /**
     * @dataProvider provideDataForTestInvalidPHPSyntax()
     */
    public function testInvalidPHPSyntax(string $content): void
    {
        $demoFormData = new DemoFormData($content, '');
        $constraints = $this->validator->validate($demoFormData);

        $this->assertCount(1, $constraints);

        /** @var ConstraintViolation $constraintViolation */
        $constraintViolation = $constraints[0];

        dump($constraintViolation->getMessage());

        $expectedMessage = sprintf('Value "%s" is not a valid PHP', $content);
        $this->assertSame($expectedMessage, $constraintViolation->getMessage());
    }

    public function provideDataForTestInvalidPHPSyntax(): Iterator
    {
        yield ['<?php echo " . '];
    }
}
