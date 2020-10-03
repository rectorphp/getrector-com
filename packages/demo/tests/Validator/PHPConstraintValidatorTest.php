<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Tests\Validator;

use Iterator;
use Rector\Website\Demo\ValueObject\DemoFormData;
use Rector\Website\GetRectorKernel;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\PackageBuilder\Tests\AbstractKernelTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

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
    public function testValidPHPSyntax(SmartFileInfo $smartFileInfo): void
    {
        $demoFormData = new DemoFormData($smartFileInfo->getContents(), '<?php echo 1;');
        $constraints = $this->validator->validate($demoFormData);

        $this->assertCount(0, $constraints);
    }

    public function provideDataForTestValidPHPSyntax(): Iterator
    {
        return StaticFixtureFinder::yieldDirectory(__DIR__ . '/Fixture', '*.php.inc');
    }

    /**
     * @dataProvider provideDataForTestMissingPHPOpeningTag()
     */
    public function testMissingPHPOpeningTag(string $content): void
    {
        $demoFormData = new DemoFormData($content, '');
        $constraints = $this->validator->validate($demoFormData);

        $this->assertCount(2, $constraints);

        /** @var ConstraintViolation $constraintViolation */
        $constraintViolation = $constraints->get(0);

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
        $demoFormData = new DemoFormData($content, '<?php echo 1;');
        $constraints = $this->validator->validate($demoFormData);

        $this->assertCount(1, $constraints);

        /** @var ConstraintViolation $constraintViolation */
        $constraintViolation = $constraints->get(0);

        /** @see https://phpunit.readthedocs.io/en/8.5/assertions.html#assertstringmatchesformat */
        $message = (string) $constraintViolation->getMessage();
        $this->assertStringMatchesFormat('Fix PHP syntax: %s', $message);
    }

    public function provideDataForTestInvalidPHPSyntax(): Iterator
    {
        yield ['<?php echo " echo . '];
    }
}
