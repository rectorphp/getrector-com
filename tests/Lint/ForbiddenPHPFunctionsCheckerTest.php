<?php declare (strict_types=1);

namespace Rector\Website\Tests\Lint;

use PHPUnit\Framework\TestCase;
use Rector\Website\Exception\ForbiddenPHPFunctionException;
use Rector\Website\Lint\ForbiddenPHPFunctionsChecker;

class ForbiddenPHPFunctionsCheckerTest extends TestCase
{
    public function testExceptionShouldBeThrown(): void
    {
        $this->expectException(ForbiddenPHPFunctionException::class);

        $checker = new ForbiddenPHPFunctionsChecker(['shell_exec']);

        $checker->checkCode('<?php shell_exec("");');
    }

    public function testCheckShouldPass(): void
    {
        $this->expectNotToPerformAssertions();

        $checker = new ForbiddenPHPFunctionsChecker(['shell_exec']);

        $checker->checkCode('<?php function("");');
    }
}
