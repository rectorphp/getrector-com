<?php

declare(strict_types=1);

namespace App\Entity;

use App\Controller\Blog\PostController;
use DateTimeInterface;

final readonly class Post
{
    public function __construct(
        private int $id,
        private string $title,
        private string $slug,
        private DateTimeInterface $dateTime,
        private string $perex,
        private string $contents,
        private string $author,
        private ?DateTimeInterface $updatedAt = null,
        private ?string $updatedMessage = null,
        private ?string $sinceRector = null
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
        $clearTitle = str_replace('&nbsp;', ' ', $clearTitle);

        // this would break urls
        return str_replace('?', '', $clearTitle);
    }

    public function getClearTitleLowercased(): string
    {
        return strtolower($this->getClearTitle());
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

    public function getAuthor(): string
    {
        return $this->author;
    }
}
