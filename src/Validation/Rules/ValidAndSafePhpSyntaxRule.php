<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Nette\Utils\Strings;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Stmt;
use PhpParser\NodeFinder;
use PhpParser\Parser;
use PhpParser\ParserFactory;

final class ValidAndSafePhpSyntaxRule implements ValidationRule
{
    /**
     * @see https://regex101.com/r/GzUnSz/1
     * @var string
     */
    private const OPENING_PHP_TAG_REGEX = '#(\s+)?\<\?php#';

    private Parser $phpParser;

    private NodeFinder $nodeFinder;

    public function __construct()
    {
        $parserFactory = new ParserFactory();
        $this->phpParser = $parserFactory->create(ParserFactory::PREFER_PHP7);

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

            if ($this->containsInclude($stmts)) {
                $fail('PHP code cannot not contain any "include"/"require" calls');
            }
        } catch (Error $error) {
            $fail('PHP code is not valid: ' . $error->getMessage());
        }
    }

    /**
     * @param Stmt[] $stmts
     */
    private function containsInclude(array $stmts): bool
    {
        $include = $this->nodeFinder->findFirst(
            $stmts,
            static fn (Node $subNode): bool => $subNode instanceof Include_
        );

        return $include instanceof Include_;
    }
}
