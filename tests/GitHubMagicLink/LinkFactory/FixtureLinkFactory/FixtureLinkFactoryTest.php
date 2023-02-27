<?php

declare(strict_types=1);

namespace Rector\Website\Tests\GitHubMagicLink\LinkFactory\FixtureLinkFactory;

use Rector\Website\GitHubMagicLink\LinkFactory\FixtureLinkFactory;
use Rector\Website\Tests\AbstractTestCase;
use Rector\Website\Tests\Helpers\DummyRectorRunFactory;

final class FixtureLinkFactoryTest extends AbstractTestCase
{
    private FixtureLinkFactory $testFixtureLinkFactory;

    private DummyRectorRunFactory $dummyRectorRunFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testFixtureLinkFactory = $this->make(FixtureLinkFactory::class);
        $this->dummyRectorRunFactory = $this->make(DummyRectorRunFactory::class);
    }

    public function test(): void
    {
        $rectorRun = $this->dummyRectorRunFactory->create();
        $testFixtureLink = $this->testFixtureLinkFactory->create($rectorRun);

        $this->assertStringMatchesFormatFile(__DIR__ . '/Fixture/expected_link.txt', $testFixtureLink . PHP_EOL);
    }
}
