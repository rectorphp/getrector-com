<?php

declare(strict_types=1);

namespace Rector\Website\Blog\ValueObject;

use DateTimeInterface;

final class Post
{
    private string $plaintextContent;

    public function __construct(
        private int $id,
        private string $title,
        private string $slug,
        private DateTimeInterface $dateTime,
        private string $perex,
        private string $htmlContent,
        private string $absoluteUrl
    ) {
        $this->plaintextContent = strip_tags($htmlContent);
    }

    public function getAbsoluteUrl(): string
    {
        return $this->absoluteUrl;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPerex(): string
    {
        return $this->perex;
    }

    public function getHtmlContent(): string
    {
        return $this->htmlContent;
    }

    public function getPlaintextContent(): string
    {
        return $this->plaintextContent;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getDateTime(): DateTimeInterface
    {
        return $this->dateTime;
    }

    public function getYear(): int
    {
        return (int) $this->dateTime->format('Y');
    }
}
