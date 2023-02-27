<?php

declare(strict_types=1);

namespace Rector\Website\Validator;

use PhpParser\Error;
use PhpParser\ParserFactory;

/**
 * @api
 */
final class PhpLinter
{
    /**
     * @see https://regex101.com/r/GzUnSz/1
     * @var string
     */
    private const OPENING_PHP_TAG_REGEX = '#(\s+)?\<\?php#';

    public function isValidPhpSyntax(string $content): bool
    {
        $patternMatch = str($content)
            ->match(self::OPENING_PHP_TAG_REGEX);
        if (! $patternMatch->value()) {
            return false;
        }

        $parserFactory = new ParserFactory();
        $parser = $parserFactory->create(ParserFactory::PREFER_PHP7);

        try {
            $parser->parse($content);

            return true;
        } catch (Error) {
            return false;
        }
    }
}
