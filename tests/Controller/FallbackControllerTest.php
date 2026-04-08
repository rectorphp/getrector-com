<?php

namespace App\Tests\Controller;

use App\Tests\AbstractTestCase;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;

class FallbackControllerTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    public function testRedirectsRuleSlug(): void
    {
        $response = $this->get('/simplify-array-search-rector');

        $response->assertRedirect('/rule-detail/simplify-array-search-rector');
    }

    public function testReturns404ForUnknownSlug(): void
    {
        $response = $this->get('/unknown-rule');

        $response->assertNotFound();
    }

    public function testRedirectsWithPascalCaseSlug(): void
    {
        $response = $this->get('/SimplifyArraySearchRector');

        $response->assertRedirect('/rule-detail/simplify-array-search-rector');
    }
}
