<?php

declare(strict_types=1);

namespace Rector\Website\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ControllerSmokeTest extends WebTestCase
{
    /**
     * @dataProvider provideUrls()
     */
    public function test(string $url): void
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertResponseIsSuccessful();
    }

    public function provideUrls(): \Iterator
    {
        yield ['/'];
        yield ['/blog'];
        yield ['/book'];
        yield ['/demo'];
        yield ['/contact'];
        yield ['/hire-team'];
    }

}
