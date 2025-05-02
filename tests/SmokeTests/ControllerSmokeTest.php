<?php

declare(strict_types=1);

namespace App\Tests\SmokeTests;

use App\Tests\AbstractTestCase;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;

final class ControllerSmokeTest extends AbstractTestCase
{
    #[RunInSeparateProcess]
    #[DataProvider('provideData')]
    public function test(string $url, int $expectedStatusCode): void
    {
        $testResponse = $this->get($url);

        $this->assertSame($expectedStatusCode, $testResponse->getStatusCode(), (string) $testResponse->getContent());
    }

    public static function provideData(): Iterator
    {
        yield ['/', 200];
        yield ['/blog', 200];
        yield ['/demo', 200];
        yield ['/contact', 200];
        yield ['/hire-team', 200];
    }
}
