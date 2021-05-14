<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Demo\Validator;

use Iterator;
use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\GetRectorKernel;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
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
        $rectorRun = $this->createRectorRun($smartFileInfo->getContents(), '<?php echo 1;');
        $constraintViolationList = $this->validator->validate($rectorRun);

        $this->assertCount(0, $constraintViolationList);
    }

    /**
     * @return Iterator<mixed>
     */
    public function provideDataForTestValidPHPSyntax(): Iterator
    {
        return StaticFixtureFinder::yieldDirectory(__DIR__ . '/Fixture', '*.php.inc');
    }

    /**
     * @dataProvider provideDataForTestMissingPHPOpeningTag()
     */
    public function testMissingPHPOpeningTag(string $content): void
    {
        $rectorRun = $this->createRectorRun($content, '');
        $constraintViolationList = $this->validator->validate($rectorRun);

        $this->assertCount(2, $constraintViolationList);

        /** @var ConstraintViolation $constraintViolation */
        $constraintViolation = $constraintViolationList->get(0);

        $this->assertSame('Add opening "<?php" tag', $constraintViolation->getMessage());
    }

    /**
     * @return Iterator<mixed>
     */
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
        $rectorRun = $this->createRectorRun($content, '<?php echo 1;');
        $constraintViolationList = $this->validator->validate($rectorRun);

        $this->assertCount(1, $constraintViolationList);

        /** @var ConstraintViolation $constraintViolation */
        $constraintViolation = $constraintViolationList->get(0);

        /** @see https://phpunit.readthedocs.io/en/8.5/assertions.html#assertstringmatchesformat */
        $message = (string) $constraintViolation->getMessage();
        $this->assertStringMatchesFormat('Fix PHP syntax: %s', $message);
    }

    /**
     * @return Iterator<mixed>
     */
    public function provideDataForTestInvalidPHPSyntax(): Iterator
    {
        yield ['<?php echo " echo . '];
    }

    private function createRectorRun(string $content, string $config): RectorRun
    {
        $rectorRun = new RectorRun();
        $rectorRun->setContent($content);
        $rectorRun->setConfig($config);

        return $rectorRun;
    }
}
