<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Nette\Utils\Json;
use Rector\Website\Demo\Validator\Constraint\PHPConstraint;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity
 */
class RectorRun implements TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $contentDiff;

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
     * @ORM\Column(type="text")
     */
    #[PHPConstraint]
    private string $config;

    /**
     * @ORM\Column(type="text")
     */
    #[PHPConstraint]
    private string $content;

    public function getId(): Uuid
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

    public function success(string $contentDiff, string $resultJson): void
    {
        $this->contentDiff = $contentDiff;
        $this->resultJson = $resultJson;
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

    public function fail(string $errorMessage): void
    {
        $this->errorMessage = $errorMessage;
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

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setConfig(string $config): void
    {
        $this->config = $config;
    }
}
