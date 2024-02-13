<?php

declare(strict_types=1);

namespace Rector\Website\ValueObject;

/**
 * @api used in the templates
 */
final class DocumentationSection
{
    public function __construct(
        private readonly string $slug,
        private readonly string $name,
        private readonly bool $isNew = false,
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

    public function isNew(): bool
    {
        return $this->isNew;
    }
}
