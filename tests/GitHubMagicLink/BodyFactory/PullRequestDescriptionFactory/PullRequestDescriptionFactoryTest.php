<?php

declare(strict_types=1);

namespace App\Tests\GitHubMagicLink\BodyFactory\PullRequestDescriptionFactory;

use App\GitHubMagicLink\BodyFactory\PullRequestDescriptionFactory;
use App\Tests\AbstractTestCase;
use App\Tests\Helpers\DummyRectorRunFactory;
use Override;

final class PullRequestDescriptionFactoryTest extends AbstractTestCase
{
    private PullRequestDescriptionFactory $pullRequestDescriptionFactory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pullRequestDescriptionFactory = $this->make(PullRequestDescriptionFactory::class);
    }

    public function test(): void
    {
        $rectorRun = DummyRectorRunFactory::create();
        $pullRequestDescription = $this->pullRequestDescriptionFactory->create($rectorRun);

        $this->assertStringMatchesFormatFile(
            __DIR__ . '/Fixture/expected_pull_request_description.txt',
            $pullRequestDescription
        );
    }
}
