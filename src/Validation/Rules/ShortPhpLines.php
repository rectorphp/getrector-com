<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use Closure;
use Illuminate\Translation\PotentiallyTranslatedString;

final class ShortPhpLines
{
    /**
     * @param Closure(string):PotentiallyTranslatedString $fail
     */
    public function validate(mixed $value, Closure $fail, int $inputLimit): void
    {
        $newlineCount = substr_count((string) $value, "\n");

        if ($newlineCount <= $inputLimit) {
            return;
        }

        $errorMessage = sprintf(
            'Content file has %d lines. Reduce it under %d lines, to make it easier to read',
            $newlineCount,
            $inputLimit
        );

        $fail($errorMessage);
    }
}
