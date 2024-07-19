<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use Nette\Utils\FileSystem;
use PHPStan\Type\ObjectType;
use ReflectionClass;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\NodeFinder;
use PhpParser\ParserFactory;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\NodeTypeResolver\PHPStan\Scope\ScopeFactory;

final class ForbiddenCallLikeRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->create(ParserFactory::PREFER_PHP7);

        $lazyContainerFactory = new LazyContainerFactory();
        $rectorConfig = $lazyContainerFactory->create();

        /** @var ScopeFactory $scopeFactory */
        $scopeFactory = $rectorConfig->make(ScopeFactory::class);
        $mutatingScope = $scopeFactory->createFromFile(__FILE__);

        try {
            $stmts = $parser->parse($value);
            $nodeFinder = new NodeFinder();

            $callLike = $nodeFinder->findFirst(
                (array) $stmts,
                function (Node $subNode) use ($mutatingScope): bool {
                    // already covered by ForbiddenFuncCallRule
                    if ($subNode instanceof FuncCall) {
                        return false;
                    }

                    // avoid extends, eg (new class extends \Nette\Utils\FileSystem {})
                    if ($subNode instanceof FullyQualified && $this->isForbidden($subNode->toString())) {
                        return true;
                    }

                    if (! $subNode instanceof CallLike) {
                        return false;
                    }

                    /** @var MethodCall|StaticCall|New_|NullsafeMethodCall $subNode */
                    $type = $subNode instanceof StaticCall
                        ? $mutatingScope->getType($mutatingScope->class)
                        : $mutatingScope->getType($subNode->var);

                    // magic!
                    if (! $type instanceof ObjectType) {
                        return true;
                    }

                    // non class should be safe
                    $className = $type->getClassName();
                    if (! class_exists($className)) {
                        return false;
                    }

                    $reflectionClass = new ReflectionClass($className);
                    if ($reflectionClass->isInternal()) {
                        return false;
                    }

                    return $this->isForbidden($className);
                }
            );

            if ($callLike instanceof CallLike) {
                $fail('PHP config should not include side effect call like');
            }
        } catch (Error $error) {
            $fail(sprintf('PHP code is invalid: %s', $error->getMessage()));
        }
    }

    private function isForbidden(string $className): bool
    {
        return in_array($className, [
            FileSystem::class,
            'Symfony\Component\Finder',
            \Symfony\Component\Filesystem\Filesystem::class
        ], true);
    }
}
