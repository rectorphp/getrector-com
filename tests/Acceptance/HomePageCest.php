<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class HomePageCest
{
    public function showHomePage(AcceptanceTester $I)
    {
        $I->amOnPage('/');

        // see text in home page
        $I->see('Automated Way to Instantly Upgrade and Refactor any PHP code');
    }
}
