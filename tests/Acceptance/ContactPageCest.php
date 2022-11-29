<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class ContactPageCest
{
    public function showContactPage(AcceptanceTester $I)
    {
        $I->amOnPage('/contact');

        // see text in contact page
        $I->see('Meet the Rector Team');
    }
}
