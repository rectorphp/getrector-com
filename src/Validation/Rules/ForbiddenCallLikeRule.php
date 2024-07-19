<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use Nette\Utils\FileSystem;
use ReflectionClass;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

final class ForbiddenCallLikeRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->create(ParserFactory::PREFER_PHP7);

        $lazyContainerFactory = new LazyContainerFactory();
        $rectorConfig = $lazyContainerFactory->create();
        /** @var NodeTypeResolver $nodeTypeResolver */
        $nodeTypeResolver = $rectorConfig->make(NodeTypeResolver::class);

        try {
            $stmts = $parser->parse($value);

            // ensure got FQCN for namespaced name
            $traverser = new NodeTraverser();
            $traverser->addVisitor(new NameResolver());

            $stmts = $traverser->traverse($stmts);

            $nodeFinder = new NodeFinder();

            $callLike = $nodeFinder->findFirst(
                (array) $stmts,
                function (Node $subNode) use ($nodeTypeResolver): bool {
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
                    if ($subNode instanceof New_) {
                        $type = $nodeTypeResolver->getType($subNode);
                    } else {
                        $type = $subNode instanceof StaticCall
                            ? $nodeTypeResolver->getType($subNode->class)
                            : $nodeTypeResolver->getType($subNode->var);
                    }
                    // fluent RectorConfigBuilder ?
                    if (! $type instanceof FullyQualifiedObjectType) {
                        if ($subNode instanceof MethodCall) {
                            $rootNode = clone $subNode->var;
                            while ($rootNode instanceof MethodCall) {
                                if ($rootNode->var instanceof StaticCall) {
                                    $rootNode = $rootNode->var->class;
                                    break;
                                }

                                if ($rootNode->var instanceof MethodCall) {
                                    $rootNode = $rootNode->var->var;
                                    continue;
                                }
                            }

                            if ($rootNode instanceof FullyQualified) {
                                $type = $nodeTypeResolver->getType($rootNode);
                            }
                        }
                    }

                    // magic!
                    if (! $type instanceof FullyQualifiedObjectType) {
                        return false;
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
