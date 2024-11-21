<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Nette\Utils\FileSystem;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use ReflectionClass;

final class ForbiddenCallLikeRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->createForNewestSupportedVersion();

        try {
            $stmts = (array) $parser->parse($value);

            // ensure got FQCN for namespaced name
            $nodeTraverser = new NodeTraverser();
            $nodeTraverser->addVisitor(new NameResolver());

            $stmts = $nodeTraverser->traverse($stmts);

            $nodeFinder = new NodeFinder();

            $callLike = $nodeFinder->findFirst(
                $stmts,
                function (Node $subNode): bool {
                    // already covered by ForbiddenFuncCallRule
                    if ($subNode instanceof FuncCall) {
                        return false;
                    }

                    // no need CallLike validation, when found a FullyQualified and forbidden, directly stop
                    // validate CallLike with Scope from PHPStan somehow cause error The item 'parameters' > env > REQUEST_TIME_FLOAT expects to be string
                    return $subNode instanceof FullyQualified && $this->isForbidden($subNode->toString());
                }
            );

            if ($callLike instanceof FullyQualified) {
                $fail('PHP config should not include side effect call like');
            }
        } catch (Error $error) {
            $fail(sprintf('PHP code is invalid: %s', $error->getMessage()));
        }
    }

    private function isForbidden(string $className): bool
    {
        if (! class_exists($className)) {
            return false;
        }

        $reflectionClass = new ReflectionClass($className);
        if ($reflectionClass->isInternal()) {
            return false;
        }

        return in_array($className, [
            FileSystem::class,
            'Symfony\Component\Finder',
            \Symfony\Component\Filesystem\Filesystem::class,
        ], true);
    }
}
