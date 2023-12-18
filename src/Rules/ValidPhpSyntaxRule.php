<?php

declare(strict_types=1);

namespace Rector\Website\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Rector\Website\Validator\PhpLinter;

final class ValidPhpSyntaxRule implements ValidationRule
{
    public function __construct(
        private readonly PhpLinter $phpLinter
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $isValid = $this->phpLinter->isValidPhpSyntax($value);
        if ($isValid) {
            return;
        }

        $fail('Provide a valid PHP code');
    }
}
