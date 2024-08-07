<?php

declare(strict_types=1);

namespace App\ValueObject;

final readonly class RenovationItem
{
    public function __construct(
        private string $title,
        private string $descriptionBefore,
        private string $descriptionAfter,
        private string $codeSnippetBefore,
        private string $codeSnippetAfter,
        private string $snippetLanguage,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescriptionBefore(): string
    {
        return $this->descriptionBefore;
    }

    public function getDescriptionAfter(): string
    {
        return $this->descriptionAfter;
    }

    public function getCodeSnippetBefore(): string
    {
        return $this->codeSnippetBefore;
    }

    public function getCodeSnippetAfter(): string
    {
        return $this->codeSnippetAfter;
    }

    public function getSnippetLanguage(): string
    {
        return $this->snippetLanguage;
    }
}
