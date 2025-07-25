<?php

declare(strict_types=1);

namespace App\Utils;

use Nette\Utils\Strings;

final class StringsConverter
{
    /**
     * @see https://regex101.com/r/5Lp2FX/1
     */
    private const string CAMEL_CASE_BY_WORD_REGEX = '#([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)#';

    public function camelCaseToGlue(string $input, string $glue): string
    {
        if ($input === strtolower($input)) {
            return $input;
        }

        $matches = Strings::matchAll($input, self::CAMEL_CASE_BY_WORD_REGEX);
        $parts = [];
        foreach ($matches as $match) {
            $parts[] = $match[0] === strtoupper((string) $match[0]) ? strtolower($match[0]) : lcfirst(
                (string) $match[0]
            );
        }

        return implode($glue, $parts);
    }
}
