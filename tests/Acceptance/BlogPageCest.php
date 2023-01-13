<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Acceptance;

use Tests\Support\AcceptanceTester;

final class BlogPageCest
{
    public function showBlogPage(AcceptanceTester $acceptanceTester): void
    {
        $acceptanceTester->amOnPage('/blog');

        // see text in blog page
        $acceptanceTester->see('Read about Rector');
    }
}
