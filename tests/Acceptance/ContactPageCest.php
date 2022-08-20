<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class ContactPageCest
{
    public function showContactPage(AcceptanceTester $I)
    {
        $I->amOnPage('/contact');

        // see text in contact page
        $I->see('Meet the Team');
    }
}
