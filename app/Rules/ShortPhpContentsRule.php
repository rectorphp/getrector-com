<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

final class ShortPhpContentsRule implements ValidationRule
{
    /**
     * @var int
     */
    private const INPUT_LINES_LIMIT = 100;

    /**
     * @param \Closure(string):PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $newlineCount = substr_count((string) $value, "\n");

        if ($newlineCount <= self::INPUT_LINES_LIMIT) {
            return;
        }

        $errorMessage = sprintf(
            'Content file has %d lines. Reduce it under %d lines, to make it easier to read',
            $newlineCount,
            self::INPUT_LINES_LIMIT
        );

        $fail($errorMessage);
    }
}
