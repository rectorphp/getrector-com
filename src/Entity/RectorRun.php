<?php

declare(strict_types=1);

namespace Rector\Website\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 */
class RectorRun
{
    /**
     * @ORM\Column(type="text")
     * @var string
     */
    private $content;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string|null
     */
    private $contentDiff;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $contentHash;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $setName;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string|null
     */
    private $resultJson;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string|null
     */
    private $errorMessage;

    /**
     * @var float|null
     * @ORM\Column(type="float", nullable=true)
     */
    private $elapsedTime;

    /**
     * @var UuidInterface
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $executedAt;

    public function __construct(UuidInterface $id, DateTimeImmutable $executedAt, string $setName, string $content)
    {
        $this->id = $id;
        $this->executedAt = $executedAt;
        $this->setName = $setName;
        $this->content = $content;
        $this->contentHash = $this->calculateContentHash($content);
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getSetName(): string
    {
        return $this->setName;
    }

    public function getContentHash(): string
    {
        return $this->contentHash ?: '';
    }

    public function getContentDiff(): string
    {
        return $this->contentDiff ?: '';
    }

    public function success(string $contentDiff, string $resultJson, float $elapsedTime): void
    {
        $this->contentDiff = $contentDiff;
        $this->resultJson = $resultJson;
        $this->elapsedTime = $elapsedTime;
    }

    public function isSuccessful(): bool
    {
        return $this->errorMessage === null && $this->resultJson !== null;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function fail(string $errorMessage, float $elapsedTime): void
    {
        $this->errorMessage = $errorMessage;
        $this->elapsedTime = $elapsedTime;
    }

    private function calculateContentHash(string $content): string
    {
        return hash('sha256', $content);
    }
}
