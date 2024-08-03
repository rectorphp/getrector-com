<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use Closure;
use Illuminate\Translation\PotentiallyTranslatedString;

/**
 * @internal
 * @method int getInputLineLimit()
 */
trait ShortPhpLinesTrait
{
    /**
     * @param Closure(string):PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $newlineCount = substr_count((string) $value, "\n");

        if ($newlineCount <= $this->getInputLineLimit()) {
            return;
        }

        $errorMessage = sprintf(
            'Content file has %d lines. Reduce it under %d lines, to make it easier to read',
            $newlineCount,
            $this->getInputLineLimit()
        );

        $fail($errorMessage);
    }
}
