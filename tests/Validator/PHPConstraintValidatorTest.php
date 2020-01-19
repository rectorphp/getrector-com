<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Validator;

use Iterator;
use Nette\Utils\FileSystem;
use Rector\Website\GetRectorKernel;
use Rector\Website\ValueObject\DemoFormData;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symplify\PackageBuilder\Tests\AbstractKernelTestCase;

/**
 * @see \Rector\Website\Validator\PHPConstraintValidator
 */
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
        $this->assertStringMatchesFormat('Fix PHP syntax: %s', $constraintViolation->getMessage());
    }

    public function provideDataForTestInvalidPHPSyntax(): Iterator
    {
        yield ['<?php echo " echo . '];
    }
}
