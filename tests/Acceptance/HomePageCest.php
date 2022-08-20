<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class HomePageCest
{
    public function showHomePage(AcceptanceTester $I)
    {
        $I->amOnPage('/');

        // see text in home page
        $I->see('Rector is a CLI tool written in PHP');
    }
}
