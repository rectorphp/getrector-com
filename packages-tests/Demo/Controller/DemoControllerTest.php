<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Demo\Controller;

use Iterator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @see \Rector\WebsiteDemo\Controller\DemoController
 * @see \Rector\WebsiteDemo\Form\DemoFormType
 */
final class DemoControllerTest extends WebTestCase
{
    public function test(): void
    {
        $this->markTestSkipped('Will be moved to Bref');

        $kernelBrowser = $this->createClient();

        // must be path of the controller
        $kernelBrowser->request('GET', '/demo');

        $this->assertResponseIsSuccessful();
    }

    /**
     * @dataProvider provideTestFormSubmitData
     */
    public function testFormSubmit(string $contentData, string $configData): void
    {
        $this->markTestSkipped('Will be moved to Bref');

        $kernelBrowser = $this->createClient();
        $kernelBrowser->request('GET', '/demo');

        # use name="..." of the form
        $kernelBrowser->submitForm('demo_form[process]', [
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
