<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Stopwatch\StopwatchEvent;

/**
 * @ORM\Entity
 */
class RectorRun
{
    /**
     * @ORM\Column(type="text")
     */
    private string $content;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $contentDiff;

    /**
     * @ORM\Column(type="text")
     */
    private string $config;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $resultJson;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $errorMessage;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $elapsedTime;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $executedAt;

    public function __construct(UuidInterface $id, DateTimeImmutable $executedAt, string $config, string $content)
    {
        $this->id = $id;
        $this->executedAt = $executedAt;
        $this->config = $config;
        $this->content = $content;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getConfig(): string
    {
        return $this->config;
    }

    public function success(string $contentDiff, string $resultJson, StopwatchEvent $stopwatchEvent): void
    {
        $this->contentDiff = $contentDiff;
        $this->resultJson = $resultJson;
        $this->updateTimeElapsed($stopwatchEvent);
    }

    public function fail(string $errorMessage, StopwatchEvent $stopwatchEvent): void
    {
        $this->errorMessage = $errorMessage;
        $this->updateTimeElapsed($stopwatchEvent);
    }

    private function updateTimeElapsed(StopwatchEvent $stopwatchEvent): void
    {
        // Convert milliseconds to seconds to be more readable
        $this->elapsedTime = $stopwatchEvent->getDuration() / 1_000;
    }
}
