<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Lint;

use Nette\Utils\Strings;
use Rector\Website\Demo\Exception\ForbiddenPHPFunctionException;
use Rector\Website\Demo\ValueObject\Option;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

/**
 * @see \Rector\Website\Demo\Tests\Lint\ForbiddenPHPFunctionsCheckerTest
 */
final class ForbiddenPHPFunctionsChecker
{
    /**
     * @var string[]
     */
    private array $forbiddenFunctions = [];


    public function __construct(ParameterProvider $parameterProvider)
    {
        $this->forbiddenFunctions = $parameterProvider->provideArrayParameter(Option::FORBIDDEN_FUNCTIONS);
    }

    public function checkCode(string $code): void
    {
        // https://regex101.com/r/4in3xJ/3
        $pattern = sprintf('#(?<function>%s)\s*\(#mi', implode('|', $this->forbiddenFunctions));
        $match = Strings::match($code, $pattern);

        if (! isset($match['function'])) {
            return;
        }

        throw new ForbiddenPHPFunctionException($match['function']);
    }
}
