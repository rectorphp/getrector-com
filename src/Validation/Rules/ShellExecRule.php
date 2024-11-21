<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Expr\ShellExec;
use PhpParser\NodeFinder;
use PhpParser\ParserFactory;

final class ShellExecRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->createForNewestSupportedVersion();

        try {
            $stmts = $parser->parse($value);
            $nodeFinder = new NodeFinder();

            $hasFuncCall = $nodeFinder->findFirst(
                (array) $stmts,
                static fn (Node $subNode): bool => $subNode instanceof ShellExec
            );

            if ($hasFuncCall instanceof Node) {
                $fail('PHP config should not include execution operator');
            }
        } catch (Error $error) {
            $fail(sprintf('PHP code is invalid: %s', $error->getMessage()));
        }
    }
}
