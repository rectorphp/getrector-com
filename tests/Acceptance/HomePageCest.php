<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Acceptance;

use Tests\Support\AcceptanceTester;

final class HomePageCest
{
    public function showHomePage(AcceptanceTester $acceptanceTester): void
    {
        $acceptanceTester->amOnPage('/');

        // see text in home page
        $acceptanceTester->see('Automated Way to Instantly Upgrade and Refactor any PHP code');
    }
}
