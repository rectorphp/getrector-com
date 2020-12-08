<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Tests\Controller;

use Iterator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @see \Rector\Website\Demo\Controller\DemoController
 * @see \Rector\Website\Demo\Form\DemoFormType
 */
final class DemoControllerTest extends WebTestCase
{
    public function test(): void
    {
        $client = static::createClient();

        // must be path of the controller
        $client->request('GET', '/demo');

        $this->assertResponseIsSuccessful();
    }

    /**
     * @dataProvider provideTestFormSubmitData
     */
    public function testFormSubmit(string $contentData, string $configData): void
    {
        $client = static::createClient();
        $client->request('GET', '/demo');

        # use name="..." of the form
        $client->submitForm('demo_form[process]', [
            'demo_form[content]' => $contentData,
            'demo_form[config]' => $configData,
        ]);

        // no redirect, because PHP was invalid
        $this->assertRouteSame('demo');

        // form should contain errors
        $this->assertSelectorExists('.invalid-feedback');
    }

    /**
     * @return Iterator<mixed>
     */
    public function provideTestFormSubmitData(): Iterator
    {
        # Send empty form
        yield ['', ''];

        # Invalid PHP syntax
        yield ['failed', 'services:'];

        # Invalid Yaml syntax
        yield ['<?php', "- 'A'\n - 'B'"];
    }
}
