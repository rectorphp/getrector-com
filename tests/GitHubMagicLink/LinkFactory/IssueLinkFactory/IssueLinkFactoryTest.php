<?php

declare(strict_types=1);

namespace Rector\Website\Tests\GitHubMagicLink\LinkFactory\IssueLinkFactory;

use Rector\Website\GitHubMagicLink\LinkFactory\IssueLinkFactory;
use Rector\Website\Tests\AbstractTestCase;
use Rector\Website\Tests\Helpers\DummyRectorRunFactory;

final class IssueLinkFactoryTest extends AbstractTestCase
{
    private IssueLinkFactory $gitHubIssueLinkFactory;

    protected function setUp(): void
    {
        $this->gitHubIssueLinkFactory = $this->make(IssueLinkFactory::class);
    }

    public function test(): void
    {
        $rectorRun = DummyRectorRunFactory::create();
        $createdLink = $this->gitHubIssueLinkFactory->create($rectorRun);

        $this->assertStringMatchesFormatFile(__DIR__ . '/Fixture/expected_link.txt', $createdLink . PHP_EOL);
    }
}
