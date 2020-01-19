<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @see \Rector\Website\Controller\DemoController
 * @see \Rector\Website\Form\DemoFormType
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

    public function testFormSubmit(): void
    {
        $client = static::createClient();
        $client->request('GET', '/demo');

        # use name="..." of the form
        $client->submitForm('demo_form[process]', [
            'demo_form[content]' => 'failed',
            'demo_form[config]' => 'services:',
        ]);

        // no redirect, because PHP was invalid
        $this->assertRouteSame('demo');
    }
}
