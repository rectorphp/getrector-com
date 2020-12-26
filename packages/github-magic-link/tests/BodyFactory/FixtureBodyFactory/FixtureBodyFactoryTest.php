<?php

declare(strict_types=1);

namespace Rector\Website\GithubMagicLink\Tests\BodyFactory\FixtureBodyFactory;

use Rector\Website\Demo\Tests\Helpers\DummyRectorRunFactory;
use Rector\Website\GetRectorKernel;
use Rector\Website\GithubMagicLink\BodyFactory\FixtureBodyFactory;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class FixtureBodyFactoryTest extends AbstractKernelTestCase
{
    private FixtureBodyFactory $fixtureBodyFactory;

    private DummyRectorRunFactory $dummyRectorRunFactory;

    protected function setUp(): void
    {
        $this->bootKernel(GetRectorKernel::class);
        $this->fixtureBodyFactory = $this->getService(FixtureBodyFactory::class);
        $this->dummyRectorRunFactory = new DummyRectorRunFactory();
    }

    public function test(): void
    {
        $rectorRun = $this->dummyRectorRunFactory->create();

        $createdLink = $this->fixtureBodyFactory->create($rectorRun);

        $issueContent = urldecode($createdLink);
        $this->assertStringMatchesFormatFile(__DIR__ . '/Fixture/expected_fixture_body.txt', $issueContent);
    }
}
