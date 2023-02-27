<?php

declare(strict_types=1);

namespace Rector\Website\Tests;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;

final class ControllerSmokeTest extends AbstractTestCase
{
    #[DataProvider('provideUrls')]
    public function test(string $url, int $expectedStatusCode): void
    {
        $response = $this->get($url);
        $response->assertStatus($expectedStatusCode);
    }

    public static function provideUrls(): Iterator
    {
        yield ['/', 200];
        yield ['/blog', 200];
        yield ['/book', 200];
        yield ['/demo', 200];
        yield ['/contact', 200];
        yield ['/hire-team', 200];
    }
}
