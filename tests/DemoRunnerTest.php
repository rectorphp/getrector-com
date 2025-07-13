<?php

declare(strict_types=1);

namespace App\Tests;

use App\DemoRunner;
use App\Entity\RectorRun;
use Override;

final class DemoRunnerTest extends AbstractTestCase
{
    private DemoRunner $demoRunner;

    #[Override]
    protected function setUp(): void
    {
        $this->demoRunner = $this->make(DemoRunner::class);
    }

    public function test(): void
    {
        $rectorRun = RectorRun::createEmpty();
        $this->demoRunner->processRectorRun($rectorRun);

        $fatalErrorMessage = $rectorRun->getFatalErrorMessage();

        $errorMessage = sprintf('Fatal error when running demo: "%s"', $fatalErrorMessage);
        $this->assertNull($fatalErrorMessage, $errorMessage);
    }
}
