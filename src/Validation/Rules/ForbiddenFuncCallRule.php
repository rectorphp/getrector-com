<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\NodeFinder;
use PhpParser\ParserFactory;
use PHPStan\TrinaryLogic;

final class ForbiddenFuncCallRule implements ValidationRule
{
    /**
     * @var string[]
     */
    private const SAFE_IMPURE_FUNCTIONS = [
        // randomize operations
        'mt_srand',
        'mt_rand',
        'rand',
        'random_int',
        'random_bytes',

        // iterator operations
        'rewind', 'iterator_apply',

        // array operations
        'array_pop', 'array_push', 'array_shift', 'array_splice', 'array_slice', 'next', 'prev', 'sort', 'ksort',

        // string operations
        'str_replace',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->createForNewestSupportedVersion();

        if (defined('PHPUNIT_COMPOSER_INSTALL')) {
            // in PHPUnit, somehow can't use include_once on multiple tests
            $functionMetadata = include 'phar://' . __DIR__ . '/../../../vendor/phpstan/phpstan/phpstan.phar/resources/functionMetadata.php';
        } else {
            $functionMetadata = include_once 'phar://' . __DIR__ . '/../../../vendor/phpstan/phpstan/phpstan.phar/resources/functionMetadata.php';
        }

        try {
            $stmts = $parser->parse($value);
            $nodeFinder = new NodeFinder();

            $funcCall = $nodeFinder->findFirst(
                (array) $stmts,
                function (Node $subNode) use ($functionMetadata): bool {
                    if (! $subNode instanceof FuncCall) {
                        return false;
                    }

                    // dynamic name? can be evil...
                    if ($subNode->name instanceof Expr) {
                        return true;
                    }

                    // only check on native function, otherwise, mark as side effect
                    $name = strtolower($subNode->name->toString());
                    if (isset($functionMetadata[$name])) {
                        $hasSideEffects = TrinaryLogic::createFromBoolean(
                            $functionMetadata[$name]['hasSideEffects']
                        );
                    } else {
                        $hasSideEffects = TrinaryLogic::createMaybe();
                    }

                    if (! $hasSideEffects->no() && in_array($name, self::SAFE_IMPURE_FUNCTIONS, true)) {
                        return false;
                    }

                    // yes() and maybe() may have side effect
                    return ! $hasSideEffects->no();
                }
            );

            if ($funcCall instanceof FuncCall) {
                $errorMessage = 'PHP config should not include side effect func call';
                if ($funcCall->name instanceof Name) {
                    $errorMessage .= sprintf(' "%s()"', $funcCall->name->toString());
                }

                $fail($errorMessage);
            }
        } catch (Error $error) {
            $fail(sprintf('PHP code is invalid: %s', $error->getMessage()));
        }
    }
}
