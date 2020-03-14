<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Tests\Validator;

use Iterator;
use Nette\Utils\FileSystem;
use Rector\Website\Demo\ValueObject\DemoFormData;
use Rector\Website\GetRectorKernel;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symplify\PackageBuilder\Tests\AbstractKernelTestCase;

/**
 * @see \Rector\Website\Demo\Validator\PHPConstraintValidator
 */
final class PHPConstraintValidatorTest extends AbstractKernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->bootKernel(GetRectorKernel::class);
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

        $classContent = FileSystem::read(__DIR__ . '/Fixture/class.php.inc');
        yield [$classContent];
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

        /** @see https://phpunit.readthedocs.io/en/8.5/assertions.html#assertstringmatchesformat */
        $message = (string) $constraintViolation->getMessage();
        $this->assertStringMatchesFormat('Fix PHP syntax: %s', $message);
    }

    public function provideDataForTestInvalidPHPSyntax(): Iterator
    {
        yield ['<?php echo " echo . '];
    }
}
