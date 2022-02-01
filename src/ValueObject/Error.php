<?php

declare(strict_types=1);

namespace Rector\Website\ValueObject;

final class Error
{
    public function __construct(
        private readonly string $message,
    ) {
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
