<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class BlogPageCest
{
    public function showBlogPage(AcceptanceTester $I)
    {
        $I->amOnPage('/blog');

        // see text in blog page
        $I->see('Rector Blog');
    }
}
