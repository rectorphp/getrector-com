<?php

declare(strict_types=1);

namespace Rector\Website\GithubMagicLink\Tests\LinkFactory\IssueLinkFactory;

use Rector\Website\Demo\Tests\Helpers\DummyRectorRunFactory;
use Rector\Website\GetRectorKernel;
use Rector\Website\GithubMagicLink\LinkFactory\IssueLinkFactory;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class IssueLinkFactoryTest extends AbstractKernelTestCase
{
    private IssueLinkFactory $gitHubIssueLinkFactory;

    private DummyRectorRunFactory $dummyRectorRunFactory;

    protected function setUp(): void
    {
        $this->bootKernel(GetRectorKernel::class);
        $this->gitHubIssueLinkFactory = $this->getService(IssueLinkFactory::class);
        $this->dummyRectorRunFactory = new DummyRectorRunFactory();
    }

    public function test(): void
    {
        $rectorRun = $this->dummyRectorRunFactory->create();
        $createdLink = $this->gitHubIssueLinkFactory->create($rectorRun);

        $this->assertStringMatchesFormatFile(__DIR__ . '/Fixture/expected_link.txt', $createdLink . PHP_EOL);
    }
}
