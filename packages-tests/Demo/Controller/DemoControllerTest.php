<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Demo\Controller;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @see \Rector\Website\Demo\Controller\DemoController
 * @see \Rector\Website\Demo\Form\DemoFormType
 */
final class DemoControllerTest extends WebTestCase
{
    #[DataProvider('provideTestFormSubmitData')]
    public function testFormSubmit(string $contentData, string $configData): void
    {
        $kernelBrowser = $this->createClient();
        $kernelBrowser->request('GET', '/demo');

        # use name="..." of the form
        $kernelBrowser->submitForm('demo_form[process]', [
            'demo_form[content]' => $contentData,
            'demo_form[config]' => $configData,
        ]);

        // no redirect, because PHP was invalid
        $this->assertRouteSame(RouteName::DEMO);

        // form should contain errors
        $this->assertSelectorExists('.invalid-feedback');
    }

    public static function provideTestFormSubmitData(): Iterator
    {
        # Send empty form
        yield ['', ''];

        # Invalid PHP syntax
        yield ['failed', 'services:'];

        # Invalid Yaml syntax
        yield ['<?php', "- 'A'\n - 'B'"];
    }
}
