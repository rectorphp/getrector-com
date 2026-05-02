<?php

namespace App\Tests\Controller;

use App\Tests\AbstractTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RuleDetailControllerTest extends AbstractTestCase
{
    public function testItRendersRulesBySlug(): void
    {
        $response = $this->get('/rule-detail/simplify-array-search-rector');

        $response
            ->assertOk()
            ->assertViewIs('homepage.rule_detail')
            ->assertViewHas('ruleMetadata')
            ->assertViewHas('codeMirror', true)
            ->assertViewHas('page_title', 'SimplifyArraySearchRector')
            ->assertSeeText('SimplifyArraySearchRector');
    }

    public function testItRedirectsToFindRuleIfRuleNotFound(): void
    {
        $response = $this->get('/rule-detail/unknown-rule');

        $response->assertRedirect('/find-rule');
    }

    public function testItRedirectsToKebabCaseSlugIfPascalCaseSlug(): void
    {
        $response = $this->get('/rule-detail/SimplifyArraySearchRector');

        $response->assertRedirect('/rule-detail/simplify-array-search-rector');
    }
}
