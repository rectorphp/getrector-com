<?php

declare(strict_types=1);

namespace Rector\Website\Tests\GitHubMagicLink\LinkFactory\IssueLinkFactory;

use Rector\Website\GitHubMagicLink\LinkFactory\IssueLinkFactory;
use Rector\Website\Tests\AbstractTestCase;
use Rector\Website\Tests\Helpers\DummyRectorRunFactory;

final class IssueLinkFactoryTest extends AbstractTestCase
{
    private IssueLinkFactory $gitHubIssueLinkFactory;

    private DummyRectorRunFactory $dummyRectorRunFactory;

    protected function setUp(): void
    {
        $this->gitHubIssueLinkFactory = $this->make(IssueLinkFactory::class);
        $this->dummyRectorRunFactory = $this->make(DummyRectorRunFactory::class);
    }

    public function test(): void
    {
        $rectorRun = $this->dummyRectorRunFactory->create();
        $createdLink = $this->gitHubIssueLinkFactory->create($rectorRun);

        $this->assertStringMatchesFormatFile(__DIR__ . '/Fixture/expected_link.txt', $createdLink . PHP_EOL);
    }
}
