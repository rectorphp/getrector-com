<?php

declare(strict_types=1);

namespace Rector\Website\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\Json;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Stopwatch\StopwatchEvent;

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
     * @ORM\Column(type="text")
     * @var string
     */
    private $config;

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
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $executedAt;

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

    public function getContentDiff(): string
    {
        return $this->contentDiff ?: '';
    }

    public function success(string $contentDiff, string $resultJson, StopwatchEvent $stopwatchEvent): void
    {
        $this->contentDiff = $contentDiff;
        $this->resultJson = $resultJson;
        $this->updateTimeElapsed($stopwatchEvent);
    }

    public function getResultJson(): ?string
    {
        return $this->resultJson;
    }

    public function isSuccessful(): bool
    {
        if ($this->errorMessage !== null) {
            return false;
        }

        return $this->resultJson !== null;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function fail(string $errorMessage, StopwatchEvent $stopwatchEvent): void
    {
        $this->errorMessage = $errorMessage;
        $this->updateTimeElapsed($stopwatchEvent);
    }

    /**
     * @return string[]
     */
    public function getAppliedRules(): array
    {
        if ($this->resultJson === null) {
            return [];
        }

        $arrayJson = Json::decode($this->resultJson, Json::FORCE_ARRAY);

        return $arrayJson['file_diffs'][0]['applied_rectors'] ?? [];
    }

    private function updateTimeElapsed(StopwatchEvent $stopwatchEvent): void
    {
        // Convert milliseconds to seconds to be more readable
        $this->elapsedTime = $stopwatchEvent->getDuration() / 1000;
    }
}
