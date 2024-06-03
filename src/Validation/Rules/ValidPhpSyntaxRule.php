<?php

declare(strict_types=1);

namespace Rector\Website\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Rector\Website\Validation\PhpLinter;

final class ValidPhpSyntaxRule implements ValidationRule
{
    public function __construct(
        private readonly PhpLinter $phpLinter
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $error = $this->phpLinter->validatePhpSyntax($value);
        if ($error === null) {
            return;
        }

        $fail(sprintf('PHP code is invalid: %s', $error));
    }
}
