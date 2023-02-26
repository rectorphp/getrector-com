<?php

namespace Rector\Website\Utils\Tests\Laravelize\TwigToBladeConverter;

use Illuminate\Container\Container;
use Nette\Utils\FileSystem;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rector\Website\Utils\Laravelize\TwigToBladeConverter;

final class TwigToBladeConverterTest extends TestCase
{
    private TwigToBladeConverter $twigToBladeConverter;

    protected function setUp(): void
    {
        $container = new Container();
        $this->twigToBladeConverter = $container->make(TwigToBladeConverter::class);

    }

    #[DataProvider('provideData')]
    public function test(string $fixtureFilePath): void
    {
        $fixtureFileContents = FileSystem::read($fixtureFilePath);

        [$inputTwigContents, $expectedBladeContents] = $this->split($fixtureFileContents);
        $convertedBladeContents = $this->twigToBladeConverter->convertFile($inputTwigContents);

        // update tests
        if (getenv('UT')) {
            FileSystem::write($fixtureFilePath, rtrim($inputTwigContents) . "\n-----\n" . $convertedBladeContents);
            $expectedBladeContents = $convertedBladeContents;
        }

        $this->assertSame($expectedBladeContents, $convertedBladeContents);
    }

    public static function provideData(): \Iterator
    {
        /** @var string[] $fixtureFilesPaths */
        $fixtureFilesPaths = glob(__DIR__ . '/Fixture/*.twig.inc');
        foreach ($fixtureFilesPaths as $fixtureFilePath) {
            yield [$fixtureFilePath];
        }
    }

    /**
     * @return array{string, string}
     */
    private function split(string $fileContents): array
    {
        $parts = str($fileContents)->split('#^\-\-\-\-\-\n#m');
        return [$parts[0], $parts[1]];
    }
}
