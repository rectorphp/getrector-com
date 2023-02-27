<?php

declare(strict_types=1);

namespace Rector\Website\Tests\GitHubMagicLink\BodyFactory\IssueBodyFactory;

use Rector\Website\GitHubMagicLink\BodyFactory\IssueBodyFactory;
use Rector\Website\Tests\AbstractTestCase;
use Rector\Website\Tests\Helpers\DummyRectorRunFactory;

final class IssueBodyFactoryTest extends AbstractTestCase
{
    private IssueBodyFactory $issueBodyFactory;

    private DummyRectorRunFactory $dummyRectorRunFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->issueBodyFactory = $this->make(IssueBodyFactory::class);
        $this->dummyRectorRunFactory = $this->make(DummyRectorRunFactory::class);
    }

    public function test(): void
    {
        $rectorRun = $this->dummyRectorRunFactory->create();
        $createdLink = $this->issueBodyFactory->create($rectorRun);

        $issueContent = urldecode($createdLink);

        $this->assertStringMatchesFormatFile(__DIR__ . '/Fixture/expected_issue_body.txt', $issueContent);
    }
}
