<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Reflection\FunctionReflection;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\NodeFinder;
use PhpParser\ParserFactory;
use Rector\DependencyInjection\LazyContainerFactory;

final class ForbiddenFuncCallRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->create(ParserFactory::PREFER_PHP7);

        $lazyContainerFactory = new LazyContainerFactory();
        $rectorContainer = $lazyContainerFactory->create();
        $phpstanReflectionProvider = $rectorContainer->make(ReflectionProvider::class);

        try {
            $stmts = $parser->parse($value);
            $nodeFinder = new NodeFinder();

            $funcCall = $nodeFinder->findFirst(
                (array) $stmts,
                function (Node $subNode) use ($phpstanReflectionProvider): bool {
                    if (! $subNode instanceof FuncCall) {
                        return false;
                    }

                    // dynamic name? can be evil...
                    if ($subNode->name instanceof Expr) {
                        return true;
                    }

                    $namespaceName = $subNode->name->getAttribute('namespaced_name');
                    $functionReflection = null;

                    if ($namespaceName instanceof FullyQualified) {
                        if ($phpstanReflectionProvider->hasFunction($namespaceName, null)) {
                            $functionReflection = $phpstanReflectionProvider->getFunction($namespaceName, null);
                        }
                    } else {
                        $functionReflection = $phpstanReflectionProvider->getFunction($subNode->name, null);
                    }

                    // another possible evil..
                    if (! $functionReflection instanceof FunctionReflection) {
                        return true;
                    }

                    return $functionReflection->hasSideEffects()->yes();
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
