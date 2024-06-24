<?php

declare(strict_types=1);

namespace Rector\Website\Tests\GitHubMagicLink\BodyFactory\FixtureBodyFactory;

use Rector\Website\GitHubMagicLink\BodyFactory\FixtureBodyFactory;
use Rector\Website\Tests\AbstractTestCase;
use Rector\Website\Tests\Helpers\DummyRectorRunFactory;

final class FixtureBodyFactoryTest extends AbstractTestCase
{
    private FixtureBodyFactory $fixtureBodyFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fixtureBodyFactory = $this->make(FixtureBodyFactory::class);
    }

    public function test(): void
    {
        $rectorRun = DummyRectorRunFactory::create();
        $createdLink = $this->fixtureBodyFactory->create($rectorRun);

        $issueContent = urldecode($createdLink);
        $this->assertStringMatchesFormatFile(__DIR__ . '/Fixture/expected_fixture_body.txt', $issueContent);
    }
}
