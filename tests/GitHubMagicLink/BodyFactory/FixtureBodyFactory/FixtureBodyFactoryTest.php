<?php

declare(strict_types=1);

namespace App\Tests\GitHubMagicLink\BodyFactory\FixtureBodyFactory;

use Override;
use App\GitHubMagicLink\BodyFactory\FixtureBodyFactory;
use App\Tests\AbstractTestCase;
use App\Tests\Helpers\DummyRectorRunFactory;

final class FixtureBodyFactoryTest extends AbstractTestCase
{
    private FixtureBodyFactory $fixtureBodyFactory;

    #[Override]
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
