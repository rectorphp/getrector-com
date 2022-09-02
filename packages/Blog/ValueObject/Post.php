<?php

declare(strict_types=1);

namespace Rector\Website\Blog\ValueObject;

use DateTimeInterface;

final class Post
{
    private readonly string $plaintextContent;

    public function __construct(
        private readonly int $id,
        private readonly string $title,
        private readonly string $slug,
        private readonly DateTimeInterface $dateTime,
        private readonly string $perex,
        private readonly string $htmlContent,
        private readonly string $absoluteUrl,
        private readonly ?string $contributor = null,
        private readonly ?int $pullRequestId = null,
        private readonly ?DateTimeInterface $updatedSince = null,
        private readonly ?string $updatedMessage = null,
        private readonly ?DateTimeInterface $deprecatedSince = null,
        private readonly ?string $deprecatedMessage = null,
        private readonly ?float $sinceRector = null
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

    public function getTweetLink(): string
    {
        return 'https://twitter.com/intent/tweet?text=' . $this->title . '&url=' . $this->absoluteUrl . '&hashtags=rectorphp';
    }

    public function getFacebookLink(): string
    {
        return 'https://www.facebook.com/sharer/sharer.php?u=' . $this->absoluteUrl;
    }

    public function getLinkedinLink(): string
    {
        return 'https://www.linkedin.com/shareArticle?url=' . $this->absoluteUrl . '/&title=' . $this->title;
    }

    public function getContributor(): ?string
    {
        return $this->contributor;
    }

    public function getPullRequestId(): ?int
    {
        return $this->pullRequestId;
    }

    public function isContribution(): bool
    {
        if ($this->contributor === null) {
            return false;
        }

        return $this->pullRequestId !== null;
    }

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

    public function getSinceRector(): ?float
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
