<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Acceptance;

use Tests\Support\AcceptanceTester;

final class ContactPageCest
{
    public function showContactPage(AcceptanceTester $acceptanceTester): void
    {
        $acceptanceTester->amOnPage('/contact');

        // see text in contact page
        $acceptanceTester->see('Meet the Rector Team');
    }
}
