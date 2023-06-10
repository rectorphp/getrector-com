<?php

declare(strict_types=1);

namespace Rector\Website\Utils\Tests\Rector\Rector\ClassMethod\SymfonyRouteAttributesToLaravelRouteFileRector;

use Nette\Utils\FileSystem;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class SymfonyRouteAttributesToLaravelRouteFileRectorTest extends AbstractRectorTestCase
{
    protected function tearDown(): void
    {
        // clear routes
        FileSystem::delete(__DIR__ . '/config/dumped_routes.php');
    }

    public function test(): void
    {
        $this->doTestFile(__DIR__ . '/Fixture/some_controller.php.inc');

        $this->assertFileExists(__DIR__ . '/config/dumped_routes.php');
        $this->assertSame(
            trim(FileSystem::read(__DIR__ . '/config/dumped_routes.php')),
            trim(FileSystem::read(__DIR__ . '/Expected/expected_dumped_routes.php'))
        );
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
