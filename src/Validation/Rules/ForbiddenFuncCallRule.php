<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\NodeFinder;
use PhpParser\ParserFactory;

final class ForbiddenFuncCallRule implements ValidationRule
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
                static fn (Node $subNode): bool => $subNode instanceof FuncCall
            );

            if ($hasFuncCall !== null) {
                $fail('PHP config should not include func call');
            }
        } catch (Error $error) {
            $fail(sprintf('PHP code is invalid: %s', $error->getMessage()));
        }
    }
}
