<?php

declare(strict_types=1);

namespace App\Tests\GitHubMagicLink\LinkFactory\IssueLinkFactory;

use Override;
use App\GitHubMagicLink\LinkFactory\IssueLinkFactory;
use App\Tests\AbstractTestCase;
use App\Tests\Helpers\DummyRectorRunFactory;

final class IssueLinkFactoryTest extends AbstractTestCase
{
    private IssueLinkFactory $gitHubIssueLinkFactory;

    #[Override]
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
