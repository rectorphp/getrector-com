<?php

declare(strict_types=1);

namespace Rector\Website\Exception;

use RuntimeException;

final class ForbiddenPHPFunctionException extends RuntimeException
{
    public function __construct(string $function)
    {
        $message = sprintf("Function '%s()' is not allowed.", $function);

        parent::__construct($message);
    }
}
