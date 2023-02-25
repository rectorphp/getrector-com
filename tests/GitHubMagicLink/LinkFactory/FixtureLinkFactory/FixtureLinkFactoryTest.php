<?php

declare(strict_types=1);

namespace Rector\Website\Tests\GitHubMagicLink\LinkFactory\FixtureLinkFactory;

use Rector\Website\GetRectorKernel;
use Rector\Website\GitHubMagicLink\LinkFactory\FixtureLinkFactory;
use Rector\Website\Tests\Demo\Helpers\DummyRectorRunFactory;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class FixtureLinkFactoryTest extends AbstractKernelTestCase
{
    private FixtureLinkFactory $testFixtureLinkFactory;

    private DummyRectorRunFactory $dummyRectorRunFactory;

    protected function setUp(): void
    {
        $this->bootKernel(GetRectorKernel::class);
        $this->testFixtureLinkFactory = $this->getService(FixtureLinkFactory::class);
        $this->dummyRectorRunFactory = new DummyRectorRunFactory();
    }

    public function test(): void
    {
        $rectorRun = $this->dummyRectorRunFactory->create();
        $testFixtureLink = $this->testFixtureLinkFactory->create($rectorRun);

        $this->assertStringMatchesFormatFile(__DIR__ . '/Fixture/expected_link.txt', $testFixtureLink . PHP_EOL);
    }
}
