<?php

declare(strict_types=1);

namespace App\Documentation;

use Nette\Utils\FileSystem;
use Webmozart\Assert\Assert;

final readonly class DocumentationMenuItem
{
    /**
     * @param non-empty-string|null $slug
     */
    public function __construct(
        private string $href,
        private string $label,
        private ?string $slug,
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

    /**
     * @return non-empty-string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getMarkdownContents(): string
    {
        Assert::notNull($this->slug);
        $documentationFilePath = __DIR__ . '/../../resources/docs/' . $this->slug . '.md';

        Assert::fileExists($documentationFilePath);
        return FileSystem::read($documentationFilePath);
    }
}
