<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Demo;

use Rector\WebsiteDemo\DemoRunner;
use Rector\WebsiteDemo\ValueObjectFactory\RectorRunFactory;
use Rector\Website\GetRectorKernel;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class DemoRunnerTest extends AbstractKernelTestCase
{
    public function test(): void
    {
        $this->bootKernel(GetRectorKernel::class);

        /** @var DemoRunner $demoRunner */
        $demoRunner = $this->getService(DemoRunner::class);

        /** @var RectorRunFactory $rectorRunFactory */
        $rectorRunFactory = $this->getService(RectorRunFactory::class);

        $rectorRun = $rectorRunFactory->createEmpty();
        $demoRunner->processRectorRun($rectorRun);

        $fatalErrorMessage = $rectorRun->getFatalErrorMessage();

        $errorMessage = sprintf('Fatal error when running demo: "%s"', $fatalErrorMessage);
        $this->assertNull($fatalErrorMessage, $errorMessage);
    }
}
