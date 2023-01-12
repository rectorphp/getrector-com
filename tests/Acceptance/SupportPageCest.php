<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Acceptance;

use Tests\Support\AcceptanceTester;

final class SupportPageCest
{
    public function showSupportPage(AcceptanceTester $acceptanceTester): void
    {
        /** @see \Rector\Website\Controller\ForCompaniesController::__invoke */
        $acceptanceTester->amOnPage('/for-companies');

        // see text in for-companies page
        $acceptanceTester->see('Hire Rector team to Reduce Costs and Technical Debt?');
    }
}
