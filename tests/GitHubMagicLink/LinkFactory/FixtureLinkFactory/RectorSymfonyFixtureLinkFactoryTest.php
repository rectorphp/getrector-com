<?php

declare(strict_types=1);

namespace Rector\Website\Tests\GitHubMagicLink\LinkFactory\FixtureLinkFactory;

use Rector\Website\GetRectorKernel;
use Rector\Website\GitHubMagicLink\LinkFactory\FixtureLinkFactory;
use Rector\Website\Tests\Helpers\StringToArrayRunFactory;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class RectorSymfonyFixtureLinkFactoryTest extends AbstractKernelTestCase
{
    private FixtureLinkFactory $testFixtureLinkFactory;

    private StringToArrayRunFactory $stringToArrayRunFactory;

    protected function setUp(): void
    {
        $this->bootKernel(GetRectorKernel::class);
        $this->testFixtureLinkFactory = $this->getService(FixtureLinkFactory::class);
        $this->stringToArrayRunFactory = new StringToArrayRunFactory();
    }

    public function test(): void
    {
        $rectorRun = $this->stringToArrayRunFactory->create();
        $testFixtureLink = $this->testFixtureLinkFactory->create($rectorRun);

        $this->assertStringMatchesFormatFile(
            __DIR__ . '/FixtureRectorSymfony/expected_link.txt',
            $testFixtureLink . PHP_EOL
        );
    }
}
