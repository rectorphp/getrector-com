<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class SupportPageCest
{
    public function showSupportPage(AcceptanceTester $I)
    {
        $I->amOnPage('/for-companies');

        // see text in for-companies page
        $I->see('Is your Project Successful, but Programmers Struggle');
    }
}
