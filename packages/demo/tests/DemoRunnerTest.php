<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Tests;

use Rector\Website\Demo\DemoRunner;
use Rector\Website\Demo\ValueObjectFactory\RectorRunFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class DemoRunnerTest extends KernelTestCase
{
    public function test(): void
    {
        $this->bootKernel();

        $container = self::$container;

        $demoRunner = $container->get(DemoRunner::class);
        $rectorRunFactory = $container->get(RectorRunFactory::class);

        $rectorRun = $rectorRunFactory->createEmpty();
        $demoRunner->processRectorRun($rectorRun);

        $fatalErrorMessage = $rectorRun->getFatalErrorMessage();

        $errorMessage = sprintf('Fatal error when running demo: "%s"', $fatalErrorMessage);
        $this->assertNull($fatalErrorMessage, $errorMessage);
    }
}
