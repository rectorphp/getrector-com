<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Acceptance;

use Tests\Support\AcceptanceTester;

final class DemoPageCest
{
    public function showDemoPage(AcceptanceTester $acceptanceTester): void
    {
        $acceptanceTester->amOnPage('/demo');

        // see text in demo page
        $acceptanceTester->see('Rector version: dev-main@');
    }

    public function clickProcessButton(AcceptanceTester $acceptanceTester): void
    {
        $acceptanceTester->amOnPage('/demo');
        $acceptanceTester->click('#demo_form_process');

        // see text in demo page after process button clicked
        $acceptanceTester->see('What did Rector change?');
        $acceptanceTester->see('-        // we never get here');
    }
}
