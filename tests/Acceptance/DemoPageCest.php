<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class DemoPageCest
{
    public function showDemoPage(AcceptanceTester $I)
    {
        $I->amOnPage('/demo');

        // see text in demo page
        $I->see('Rector version: dev-main@');
    }

    public function clickProcessButton(AcceptanceTester $I)
    {
        $I->amOnPage('/demo');
        $I->click('#demo_form_process');

        // see text in demo page after process button clicked
        $I->see('What did Rector change?');
        $I->see('-        // we never get here');
    }
}
