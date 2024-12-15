<?php

declare(strict_types=1);

namespace App\Documentation;

final readonly class DocumentationMenuItem
{
    public function __construct(
        private string $href,
        private string $label,
        private bool $isNew = false,
    ) {
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function isNew(): bool
    {
        return $this->isNew;
    }
}
