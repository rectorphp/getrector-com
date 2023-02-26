<?php

namespace Rector\Website\Utils\Tests\Laravelize\TwigToBladeConverter;

use Illuminate\Container\Container;
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

    public function test(): void
    {
        $this->twigToBladeConverter->convertFile(__DIR__ . '/Source/SomeTwigFile.twig');
    }
}
