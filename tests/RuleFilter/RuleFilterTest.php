<?php

declare(strict_types=1);

namespace App\Tests\RuleFilter;

use App\Enum\FindRule\GroupName;
use App\RuleFilter\RuleFilter;
use App\RuleFilter\ValueObject\RuleMetadata;
use App\Tests\AbstractTestCase;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use RectorLaravel\Rector\ClassMethod\MigrateToSimplifiedAttributeRector;

final class RuleFilterTest extends AbstractTestCase
{
    public function testFilterBySetGroup(): void
    {
        $ruleMetadataCore = new RuleMetadata(
            RenameMethodRector::class,
            'Some description',
            [],
            [],
            'some-rector.php',
        );
        $ruleMetadataCommunity = new RuleMetadata(
            MigrateToSimplifiedAttributeRector::class,
            'Some description',
            [],
            [],
            'some-rector.php',
        );

        $ruleFilter = $this->make(RuleFilter::class);
        $filtered = $ruleFilter->filter(
            [$ruleMetadataCore, $ruleMetadataCommunity],
            null,
            null,
            GroupName::LARAVEL,
        );

        $this->assertCount(1, $filtered);
        $this->assertSame($ruleMetadataCommunity->getRectorClass(), $filtered[0]->getRectorClass());
    }
}
