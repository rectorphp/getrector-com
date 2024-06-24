<?php

declare(strict_types=1);

namespace Rector\Website\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Expr\Include_;
use PhpParser\NodeFinder;
use PhpParser\ParserFactory;

final class IncludeRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->create(ParserFactory::PREFER_PHP7);

        try {
            $stmts = $parser->parse($value);
            $nodeFinder = new NodeFinder();

            $hasFuncCall = $nodeFinder->findFirst(
                (array) $stmts,
                static fn (Node $subNode): bool => $subNode instanceof Include_
            );

            if ($hasFuncCall !== null) {
                $fail('PHP config should not include include/require usage');
            }
        } catch (Error $error) {
            $fail(sprintf('PHP code is invalid: %s', $error->getMessage()));
        }
    }
}
