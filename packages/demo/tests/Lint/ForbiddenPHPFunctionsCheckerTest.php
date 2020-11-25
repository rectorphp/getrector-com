<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Tests\Lint;

use Rector\Website\Demo\Exception\ForbiddenPHPFunctionException;
use Rector\Website\Demo\Lint\ForbiddenPHPFunctionsChecker;
use Rector\Website\GetRectorKernel;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class ForbiddenPHPFunctionsCheckerTest extends AbstractKernelTestCase
{
    private ForbiddenPHPFunctionsChecker $forbiddenPHPFunctionsChecker;

    protected function setUp(): void
    {
        $this->bootKernel(GetRectorKernel::class);
        $this->forbiddenPHPFunctionsChecker = self::$container->get(ForbiddenPHPFunctionsChecker::class);
    }

    public function testExceptionShouldBeThrown(): void
    {
        $this->expectException(ForbiddenPHPFunctionException::class);
        $this->forbiddenPHPFunctionsChecker->checkCode('<?php shell_exec("");');
    }

    public function testCheckShouldPass(): void
    {
        $this->expectNotToPerformAssertions();
        $this->forbiddenPHPFunctionsChecker->checkCode('<?php function("");');
    }
}
