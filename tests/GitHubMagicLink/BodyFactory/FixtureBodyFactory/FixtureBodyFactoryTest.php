<?php

declare(strict_types=1);

namespace Rector\Website\Tests\GitHubMagicLink\BodyFactory\FixtureBodyFactory;

use Rector\Website\GitHubMagicLink\BodyFactory\FixtureBodyFactory;
use Rector\Website\Tests\AbstractTestCase;
use Rector\Website\Tests\Helpers\DummyRectorRunFactory;

final class FixtureBodyFactoryTest extends AbstractTestCase
{
    private FixtureBodyFactory $fixtureBodyFactory;

    private DummyRectorRunFactory $dummyRectorRunFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fixtureBodyFactory = $this->make(FixtureBodyFactory::class);
        $this->dummyRectorRunFactory = $this->make(DummyRectorRunFactory::class);
    }

    public function test(): void
    {
        $rectorRun = $this->dummyRectorRunFactory->create();

        $createdLink = $this->fixtureBodyFactory->create($rectorRun);

        $issueContent = urldecode($createdLink);
        $this->assertStringMatchesFormatFile(__DIR__ . '/Fixture/expected_fixture_body.txt', $issueContent);
    }
}
