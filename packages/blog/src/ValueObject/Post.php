<?php

declare(strict_types=1);

namespace Rector\Website\Blog\ValueObject;

use DateTimeInterface;

final class Post
{
    private int $id;

    private string $absoluteUrl;

    private string $title;

    private string $perex;

    private string $htmlContent;

    private string $plaintextContent;

    private string $slug;

    private string $sourceRelativePath;

    private DateTimeInterface $dateTime;

    public function __construct(
        int $id,
        string $title,
        string $slug,
        DateTimeInterface $dateTime,
        string $perex,
        string $htmlContent,
        string $sourceRelativePath,
        string $absoluteUrl
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->perex = $perex;
        $this->htmlContent = $htmlContent;
        $this->plaintextContent = strip_tags($htmlContent);
        $this->slug = $slug;
        $this->dateTime = $dateTime;
        $this->sourceRelativePath = $sourceRelativePath;
        $this->absoluteUrl = $absoluteUrl;
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
