<?php
declare(strict_types=1);

namespace Rector\Website\Demo\Tests;

use Rector\Website\Demo\DemoRunner;
use Rector\Website\Demo\ValueObjectFactory\RectorRunFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DemoRunnerTest extends KernelTestCase
{
    public function test(): void
    {
        self::bootKernel();

        $container = self::$container;

        $demoRunner = $container->get(DemoRunner::class);
        $rectorRunFactory = $container->get(RectorRunFactory::class);

        $rectorRun = $rectorRunFactory->createEmpty();

        $demoRunner->processRectorRun($rectorRun);

        self::assertNull($rectorRun->getFatalErrorMessage());
    }
}
