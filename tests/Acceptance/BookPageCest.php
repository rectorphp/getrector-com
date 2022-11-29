<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Acceptance;

use Tests\Support\AcceptanceTester;

final class BookPageCest
{
    public function showBookPage(AcceptanceTester $acceptanceTester): void
    {
        $acceptanceTester->amOnPage('/book');

        // see text in book page
        $acceptanceTester->see('The Power of Automated Refactoring');
    }
}
