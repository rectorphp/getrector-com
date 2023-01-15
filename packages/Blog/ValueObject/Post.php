<?php

declare(strict_types=1);

namespace Rector\Website\Blog\ValueObject;

use DateTimeInterface;

final class Post
{
    public function __construct(
        private readonly int $id,
        private readonly string $title,
        private readonly string $slug,
        private readonly DateTimeInterface $dateTime,
        private readonly string $perex,
        private readonly string $htmlContent,
<<<<<<< HEAD
<<<<<<< HEAD
=======
        private readonly string $absoluteUrl,
>>>>>>> adbd795 (simplify contributors)
=======
>>>>>>> bcc676d (cleanup post from routers)
        private readonly ?DateTimeInterface $updatedSince = null,
        private readonly ?string $updatedMessage = null,
        private readonly ?DateTimeInterface $deprecatedSince = null,
        private readonly ?string $deprecatedMessage = null,
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

    public function getPerex(): string
    {
        return $this->perex;
    }

    public function getHtmlContent(): string
    {
        return $this->htmlContent;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getDateTime(): DateTimeInterface
    {
        return $this->dateTime;
    }

<<<<<<< HEAD
<<<<<<< HEAD
=======
    public function getTweetLink(): string
    {
        return 'https://twitter.com/intent/tweet?text=' . $this->title . '&url=' . $this->absoluteUrl . '&hashtags=rectorphp';
    }

    public function getFacebookLink(): string
    {
        return 'https://www.facebook.com/sharer/sharer.php?u=' . $this->absoluteUrl;
    }

>>>>>>> adbd795 (simplify contributors)
=======
>>>>>>> bf79931 (cleanup share links, not really used)
    public function isUpdated(): bool
    {
        return $this->updatedSince !== null;
    }

    public function isDeprecated(): bool
    {
        return $this->deprecatedSince !== null;
    }

    public function getUpdatedSince(): ?DateTimeInterface
    {
        return $this->updatedSince;
    }

    public function getUpdatedMessage(): ?string
    {
        return $this->updatedMessage;
    }

    public function getSinceRector(): ?string
    {
        return $this->sinceRector;
    }

    public function getDeprecatedSince(): ?DateTimeInterface
    {
        return $this->deprecatedSince;
    }

    public function getDeprecatedMessage(): ?string
    {
        return $this->deprecatedMessage;
    }
}
