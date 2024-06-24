<?php

declare(strict_types=1);

namespace App\Tests\GitHubMagicLink\BodyFactory\IssueBodyFactory;

use App\GitHubMagicLink\BodyFactory\IssueBodyFactory;
use App\Tests\AbstractTestCase;
use App\Tests\Helpers\DummyRectorRunFactory;

final class IssueBodyFactoryTest extends AbstractTestCase
{
    private IssueBodyFactory $issueBodyFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->issueBodyFactory = $this->make(IssueBodyFactory::class);
    }

    public function test(): void
    {
        $rectorRun = DummyRectorRunFactory::create();
        $createdLink = $this->issueBodyFactory->create($rectorRun);

        $issueContent = urldecode($createdLink);

        $this->assertStringMatchesFormatFile(__DIR__ . '/Fixture/expected_issue_body.txt', $issueContent);
    }
}
