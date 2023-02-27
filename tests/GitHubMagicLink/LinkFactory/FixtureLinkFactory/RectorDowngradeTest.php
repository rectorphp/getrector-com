<?php

declare(strict_types=1);

namespace Rector\Website\Tests\GitHubMagicLink\LinkFactory\FixtureLinkFactory;

use Rector\Website\GitHubMagicLink\LinkFactory\FixtureLinkFactory;
use Rector\Website\Tests\AbstractTestCase;
use Rector\Website\Tests\Helpers\DowngradeArrayIsListFactory;

final class RectorDowngradeTest extends AbstractTestCase
{
    private FixtureLinkFactory $testFixtureLinkFactory;

    private DowngradeArrayIsListFactory $downgradeArrayIsListFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testFixtureLinkFactory = $this->make(FixtureLinkFactory::class);
        $this->downgradeArrayIsListFactory = $this->make(DowngradeArrayIsListFactory::class);
    }

    public function test(): void
    {
        $rectorRun = $this->downgradeArrayIsListFactory->create();
        $testFixtureLink = $this->testFixtureLinkFactory->create($rectorRun);

        $this->assertStringMatchesFormatFile(
            __DIR__ . '/FixtureRectorDowngrade/expected_link.txt',
            $testFixtureLink . PHP_EOL
        );
    }
}
