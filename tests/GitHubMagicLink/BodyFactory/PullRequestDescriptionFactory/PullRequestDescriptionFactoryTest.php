<?php

declare(strict_types=1);

namespace Rector\Website\Tests\GitHubMagicLink\BodyFactory\PullRequestDescriptionFactory;

use Rector\Website\GitHubMagicLink\BodyFactory\PullRequestDescriptionFactory;
use Rector\Website\Tests\AbstractTestCase;
use Rector\Website\Tests\Helpers\DummyRectorRunFactory;

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
