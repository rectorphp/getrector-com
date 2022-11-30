<?php

declare(strict_types=1);

namespace Rector\Website\ValueObject;

final class DocumentationSection
{
    public function __construct(
        private string $slug,
        private string $name,
    ) {
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
