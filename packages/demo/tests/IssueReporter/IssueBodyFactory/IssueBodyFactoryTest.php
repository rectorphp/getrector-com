<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Tests\IssueReporter\IssueBodyFactory;

use Rector\Website\Demo\IssueReporter\IssueBodyFactory;
use Rector\Website\Demo\Tests\Helpers\DummyRectorRunFactory;
use Rector\Website\GetRectorKernel;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class IssueBodyFactoryTest extends AbstractKernelTestCase
{
    private IssueBodyFactory $issueBodyFactory;

    protected function setUp(): void
    {
        $this->bootKernel(GetRectorKernel::class);
        $this->issueBodyFactory = $this->getService(IssueBodyFactory::class);
    }

    public function test(): void
    {
        $dummyRectorRunFactory = new DummyRectorRunFactory();
        $rectorRun = $dummyRectorRunFactory->create();

        $createdLink = $this->issueBodyFactory->create($rectorRun);

        $issueContent = urldecode($createdLink);
        $this->assertStringMatchesFormatFile(__DIR__ . '/Fixture/expected_issue_body.txt', $issueContent);
    }
}
