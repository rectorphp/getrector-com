<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

final class ShortPhpContentsRule implements ValidationRule
{
    /**
     * @var int
     */
    private const INPUT_LINES_LIMIT = 100;

    public function __construct(
        private readonly ShortPhpLines $shortPhpLines
    ) {
    }

    /**
     * @param Closure(string):PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $this->shortPhpLines->validate($value, $fail, self::INPUT_LINES_LIMIT);
    }
}
