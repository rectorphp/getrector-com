<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Nette\Utils\Strings;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Expr\Exit_;
use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Stmt;
use PhpParser\NodeFinder;
use PhpParser\Parser;
use PhpParser\ParserFactory;

final readonly class ValidAndSafePhpSyntaxRule implements ValidationRule
{
    /**
     * @see https://regex101.com/r/GzUnSz/1
     */
    private const string OPENING_PHP_TAG_REGEX = '#(\s+)?\<\?php#';

    private Parser $phpParser;

    private NodeFinder $nodeFinder;

    public function __construct()
    {
        $parserFactory = new ParserFactory();
        $this->phpParser = $parserFactory->createForNewestSupportedVersion();

        $this->nodeFinder = new NodeFinder();
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $patternMatch = Strings::match($value, self::OPENING_PHP_TAG_REGEX);

        if ($patternMatch === null) {
            $fail('PHP code is invalid: Missing PHP opening tag');
            return;
        }

        try {
            $stmts = $this->phpParser->parse($value);
            if ($stmts === null) {
                $fail('PHP code is not valid');
                return;
            }

            if ($this->containsIncludeOrExit($stmts)) {
                $fail('PHP code cannot contain any "include"/"require"/"exit" calls');
            }
        } catch (Error $error) {
            $fail('PHP code is invalid: ' . $error->getMessage());
        }
    }

    /**
     * @param Stmt[] $stmts
     */
    private function containsIncludeOrExit(array $stmts): bool
    {
        $includeOrExit = $this->nodeFinder->findFirst(
            $stmts,
            static fn (Node $subNode): bool => $subNode instanceof Include_ || $subNode instanceof Exit_
        );

        return $includeOrExit instanceof Include_ || $includeOrExit instanceof Exit_;
    }
}
