<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\NodeFinder;
use PhpParser\ParserFactory;
use PHPStan\TrinaryLogic;

final class ForbiddenFuncCallRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->create(ParserFactory::PREFER_PHP7);

        $functionMetadata = include_once 'phar://' . __DIR__ . '/../../../vendor/phpstan/phpstan/phpstan.phar/resources/functionMetadata.php';

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

                    $namespaceName = $subNode->name->getAttribute('namespaced_name');

                    if ($namespaceName instanceof FullyQualified) {
                        $name = strtolower($namespaceName->toString());
                    } else {
                        $name = strtolower($subNode->name->toString());
                    }

                    if (isset($functionMetadata[$name])) {
                        $hasSideEffects = TrinaryLogic::createFromBoolean(
                            $functionMetadata[$name]['hasSideEffects']
                        );
                    } else {
                        // possibly unknown
                        $hasSideEffects = TrinaryLogic::createYes();
                    }

                    return $hasSideEffects->yes();
                }
            );

            if ($funcCall instanceof FuncCall) {
                $fail('PHP config should not include side effect func call');
            }
        } catch (Error $error) {
            $fail(sprintf('PHP code is invalid: %s', $error->getMessage()));
        }
    }
}
