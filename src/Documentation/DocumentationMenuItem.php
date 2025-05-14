<?php

declare(strict_types=1);

namespace App\Documentation;

use Nette\Utils\FileSystem;
use Webmozart\Assert\Assert;

final readonly class DocumentationMenuItem
{
    /**
     * @param string $href The URL path for the documentation menu item. Must be a non-empty string starting with '/'
     * @param string $label The display text for the menu item. Must be a non-empty string
     * @param non-empty-string|null $slug The unique identifier for the documentation file. If null, indicates a menu item without content
     * @param bool $isNew Whether this menu item represents new documentation
     */
    public function __construct(
        private string $href,
        private string $label,
        private ?string $slug,
        private bool $isNew = false,
    ) {
        Assert::notEmpty($href, 'Documentation href cannot be empty');
        Assert::startsWith($href, '/', 'Documentation href must start with a forward slash');
        Assert::notEmpty($label, 'Documentation label cannot be empty');
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

    /**
     * Checks if the documentation file exists for this menu item.
     * 
     * @return bool True if the documentation file exists and is readable, false otherwise
     */
    public function hasDocumentation(): bool
    {
        if ($this->slug === null) {
            return false;
        }

        $documentationFilePath = $this->getDocumentationFilePath();
        return file_exists($documentationFilePath) && is_readable($documentationFilePath);
    }

    public function getMarkdownContents(): string
    {
        Assert::notNull($this->slug);
        $documentationFilePath = $this->getDocumentationFilePath();

        Assert::fileExists($documentationFilePath);
        return FileSystem::read($documentationFilePath);
    }

    /**
     * Gets the full path to the documentation file.
     * 
     * @return string The absolute path to the documentation file
     */
    private function getDocumentationFilePath(): string
    {
        return __DIR__ . '/../../resources/docs/' . $this->slug . '.md';
    }
}
