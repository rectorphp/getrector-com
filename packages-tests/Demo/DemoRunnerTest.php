<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Demo;

use Rector\Website\Demo\DemoRunner;
use Rector\Website\Demo\ValueObjectFactory\RectorRunFactory;
use Rector\Website\GetRectorKernel;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class DemoRunnerTest extends AbstractKernelTestCase
{
    private DemoRunner $demoRunner;

    private RectorRunFactory $rectorRunFactory;

    protected function setUp(): void
    {
        $this->bootKernel(GetRectorKernel::class);

        $this->demoRunner = $this->getService(DemoRunner::class);
        $this->rectorRunFactory = $this->getService(RectorRunFactory::class);
    }


    public function test(): void
    {
        $rectorRun = $this->rectorRunFactory->createEmpty();
        $this->demoRunner->processRectorRun($rectorRun);

        $fatalErrorMessage = $rectorRun->getFatalErrorMessage();

        $errorMessage = sprintf('Fatal error when running demo: "%s"', $fatalErrorMessage);
        $this->assertNull($fatalErrorMessage, $errorMessage);
    }
}
