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
        private string $absoluteUrl,
        private ?string $contributor = null,
        private ?int $pullRequestId = null,
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
}
