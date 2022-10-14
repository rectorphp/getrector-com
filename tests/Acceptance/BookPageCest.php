<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class BookPageCest
{
    public function showBookPage(AcceptanceTester $I)
    {
        $I->amOnPage('/book');

        // see text in book page
        $I->see('The Power of Automated Refactoring');
    }
}
