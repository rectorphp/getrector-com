<?php

declare(strict_types=1);

namespace Rector\Website\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\NodeFinder;
use PhpParser\ParserFactory;

final class HasRectorRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->create(ParserFactory::PREFER_PHP7);

        try {
            $stmts = $parser->parse($value);
            $nodeFinder = new NodeFinder();

            $hasRectorRule = $nodeFinder->findFirst(
                (array) $stmts,
                static fn (Node $subNode): bool => $subNode instanceof MethodCall
                    && $subNode->name instanceof Identifier
                    && in_array(
                        $subNode->name->toString(),
                        [
                            'rule',
                            'ruleWithConfiguration',
                            'rules',
                            'import',
                            'sets',
                            'withSets',
                            'withAttributesSets',
                            'withPhpSets',
                            'withPreparedSets',
                            'withRules',
                            'withConfiguredRule',
                        ],
                        true
                    )
            );

            if ($hasRectorRule === null) {
                $fail('PHP config should include at least 1 rector rule');
            }
        } catch (Error $error) {
            $fail(sprintf('PHP code is invalid: %s', $error->getMessage()));
        }
    }
}
