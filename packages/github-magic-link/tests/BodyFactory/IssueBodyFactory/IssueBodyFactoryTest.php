<?php

declare(strict_types=1);

namespace Rector\Website\GithubMagicLink\Tests\BodyFactory\IssueBodyFactory;

use Rector\Website\Demo\Tests\Helpers\DummyRectorRunFactory;
use Rector\Website\GetRectorKernel;
use Rector\Website\GithubMagicLink\BodyFactory\IssueBodyFactory;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class IssueBodyFactoryTest extends AbstractKernelTestCase
{
    private IssueBodyFactory $issueBodyFactory;

    private DummyRectorRunFactory $dummyRectorRunFactory;

    protected function setUp(): void
    {
        $this->bootKernel(GetRectorKernel::class);
        $this->issueBodyFactory = $this->getService(IssueBodyFactory::class);
        $this->dummyRectorRunFactory = new DummyRectorRunFactory();
    }

    public function test(): void
    {
        $rectorRun = $this->dummyRectorRunFactory->create();
        $createdLink = $this->issueBodyFactory->create($rectorRun);

        $issueContent = urldecode($createdLink);

        $this->assertStringMatchesFormatFile(__DIR__ . '/Fixture/expected_issue_body.txt', $issueContent);
    }
}
