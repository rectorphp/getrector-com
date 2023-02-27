<?php

declare(strict_types=1);

namespace Rector\Website\Tests;

use Rector\Website\DemoRunner;
use Rector\Website\EntityFactory\RectorRunFactory;

final class DemoRunnerTest extends AbstractTestCase
{
    private DemoRunner $demoRunner;

    private RectorRunFactory $rectorRunFactory;

    protected function setUp(): void
    {
        $this->demoRunner = $this->make(DemoRunner::class);
        $this->rectorRunFactory = $this->make(RectorRunFactory::class);
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
