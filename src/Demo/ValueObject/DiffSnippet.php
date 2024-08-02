<?php

declare(strict_types=1);

namespace App\Demo\ValueObject;

final readonly class DiffSnippet
{
    public function __construct(
        private int $line,
        private string $snippet
    ) {
    }

    public function getLine(): int
    {
        return $this->line;
    }

    public function getSnippet(): string
    {
        return $this->snippet;
    }
}
