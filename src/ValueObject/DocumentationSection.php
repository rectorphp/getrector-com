<?php

declare(strict_types=1);

namespace Rector\Website\ValueObject;

final class DocumentationSection
{
    public function __construct(
        private readonly string $slug,
        private readonly string $name,
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
