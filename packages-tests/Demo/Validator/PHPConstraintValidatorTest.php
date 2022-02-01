<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Demo\Validator;

use Iterator;
use PHPUnit\Framework\TestCase;
use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\Demo\Lint\PHPLinter;
use Rector\Website\Demo\Validator\PHPConstraintValidator;
use Rector\Website\Exception\ShouldNotHappenException;
use Rector\Website\GetRectorKernel;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;

/**
 * @see \Rector\Website\Demo\Validator\PHPConstraintValidator
 */
final class PHPConstraintValidatorTest extends TestCase
{
    private PHPConstraintValidator $phpConstraintValidator;

    protected function setUp(): void
    {
        $this->phpConstraintValidator = new PHPConstraintValidator(
            new PHPLinter(new SmartFileSystem())
        );
    }

    /**
     * @doesNotPerformAssertions
     * @dataProvider provideDataForTestValidPHPSyntax()
     */
    public function testValidPHPSyntax(SmartFileInfo $smartFileInfo): void
    {
        $this->phpConstraintValidator->validate($smartFileInfo->getContents());
    }

    /**
     * @return Iterator<mixed>
     */
    public function provideDataForTestValidPHPSyntax(): Iterator
    {
        return StaticFixtureFinder::yieldDirectory(__DIR__ . '/Fixture');
    }

    /**
     * @dataProvider provideDataForTestMissingPHPOpeningTag()
     */
    public function testMissingPHPOpeningTag(string $content): void
    {
        $this->expectException(ShouldNotHappenException::class);
        $this->expectExceptionMessage('Add opening "<?php" tag');

        $this->phpConstraintValidator->validate($content);
    }

    /**
     * @return Iterator<mixed>
     */
    public function provideDataForTestMissingPHPOpeningTag(): Iterator
    {
        yield ['php echo'];
        yield ['echo'];
    }

    public function testInvalidPHPSyntax(): void
    {
        $this->expectException(ShouldNotHappenException::class);

        /** @see https://phpunit.readthedocs.io/en/8.5/assertions.html#assertstringmatchesformat */
        $this->expectExceptionMessageMatches('#Fix PHP syntax: (.*?)#');

        $this->phpConstraintValidator->validate('<?php echo " echo . ');
    }
}
