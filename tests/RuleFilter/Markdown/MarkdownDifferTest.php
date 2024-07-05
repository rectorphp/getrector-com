<?php

namespace App\Tests\RuleFilter\Markdown;

use App\RuleFilter\Markdown\MarkdownDiffer;
use App\Tests\AbstractTestCase;

final class MarkdownDifferTest extends AbstractTestCase
{
    public function test(): void
    {
        $markdownDiffer = $this->make(MarkdownDiffer::class);
        $diff = $markdownDiffer->diff('some old', 'some new');

        $this->assertSame(<<<EXPECTED_DIFF
-some old
+some new
EXPECTED_DIFF
            , $diff);
    }
}
