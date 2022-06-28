<?php

declare(strict_types=1);

namespace Rector\Website\Tests\GitHubMagicLink\LinkFactory\FixtureLinkFactory;

use Rector\Website\Tests\Demo\Helpers\DummyRectorRunFactory;
use Rector\Website\GetRectorKernel;
use Rector\Website\GitHubMagicLink\LinkFactory\FixtureLinkFactory;
use Rector\Website\Tests\Demo\Helpers\DowngradeArrayIsListFactory;
use Rector\Website\Tests\Demo\Helpers\StringToArrayRunFactory;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class RectorDowngradeTest extends AbstractKernelTestCase
{
    private FixtureLinkFactory $testFixtureLinkFactory;

    private DowngradeArrayIsListFactory $downgradeArrayIsListFactory;

    protected function setUp(): void
    {
        $this->bootKernel(GetRectorKernel::class);
        $this->testFixtureLinkFactory = $this->getService(FixtureLinkFactory::class);
        $this->downgradeArrayIsListFactory = new DowngradeArrayIsListFactory();
    }

    public function test(): void
    {
        $rectorRun = $this->downgradeArrayIsListFactory->create();
        $testFixtureLink = $this->testFixtureLinkFactory->create($rectorRun);

        $this->assertStringMatchesFormatFile(__DIR__ . '/FixtureRectorDowngrade/expected_link.txt', $testFixtureLink . PHP_EOL);
    }
}
