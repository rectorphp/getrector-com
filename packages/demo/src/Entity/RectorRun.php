<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Jean85\Version;
use Nette\Utils\Json;
use Nette\Utils\Strings;
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

    public function getContentDiff(): string
    {
        return $this->contentDiff ?: '';
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

    public function getVersion(): ?Version
    {
        if (! $this->resultJson) {
            return null;
        }

        $data = Json::decode($this->resultJson, Json::FORCE_ARRAY);

        if (! isset($data['meta']['version'])) {
            return null;
        }

        $version = $data['meta']['version'];

        // Creating `new Version()` would fail if version does not contain `@`
        if (Strings::contains($version, '@') === false) {
            $version .= '@unknown';
        }

        return new Version('rector/rector', $version);
    }

    /**
     * @return mixed[]
     */
    public function getAppliedRules(): array
    {
        if ($this->resultJson === null) {
            return [];
        }

        $arrayJson = Json::decode($this->resultJson, Json::FORCE_ARRAY);

        $result = $arrayJson['file_diffs'][0]['applied_rectors'] ?? [];
        return (array) $result;
    }

    private function updateTimeElapsed(StopwatchEvent $stopwatchEvent): void
    {
        // Convert milliseconds to seconds to be more readable
        $this->elapsedTime = $stopwatchEvent->getDuration() / 1_000;
    }
}
