<?php

declare(strict_types=1);

namespace Rector\Website\Entity;

use DateTimeInterface;
use Rector\Website\Http\Controller\Blog\PostController;

final class Post
{
    public function __construct(
        private readonly int $id,
        private readonly string $title,
        private readonly string $slug,
        private readonly DateTimeInterface $dateTime,
        private readonly string $perex,
        private readonly string $contents,
        private readonly ?DateTimeInterface $updatedAt = null,
        private readonly ?string $updatedMessage = null,
        private readonly ?string $sinceRector = null
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getClearTitle(): string
    {
        $clearTitle = strip_tags($this->title);
        return str_replace('&nbsp;', ' ', $clearTitle);
    }

    public function getPerex(): string
    {
        return $this->perex;
    }

    public function getContents(): string
    {
        return $this->contents;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getDateTime(): DateTimeInterface
    {
        return $this->dateTime;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getUpdatedMessage(): ?string
    {
        return $this->updatedMessage;
    }

    public function getSinceRector(): ?string
    {
        return $this->sinceRector;
    }

    public function getAbsoluteUrl(): string
    {
        return action(PostController::class, [
            'postSlug' => $this->slug,
        ]);
    }

    public function hasTweets(): bool
    {
        return str_contains($this->contents, 'class="twitter-tweet"');
    }
}
