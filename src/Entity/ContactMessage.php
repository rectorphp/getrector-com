<?php

declare(strict_types=1);

namespace Rector\Website\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity
 */
class ContactMessage implements TimestampableInterface
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
     * @ORM\Column(type="text")
     */
    private string $message;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="string")
     */
    private string $email;

    /**
     * @ORM\Column(type="integer")
     */
    private int $projectSize;

    /**
     * @ORM\Column(type="string")
     */
    private string $framework;

    /**
     * @ORM\Column(type="integer")
     */
    private int $currentPhpVersion;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $targetPhpVersion = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isReplied = false;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getProjectSize(): int
    {
        return $this->projectSize;
    }

    public function setProjectSize(int $projectSize): void
    {
        $this->projectSize = $projectSize;
    }

    public function getFramework(): string
    {
        return $this->framework;
    }

    public function setFramework(string $framework): void
    {
        $this->framework = $framework;
    }

    public function getCurrentPhpVersion(): int
    {
        return $this->currentPhpVersion;
    }

    public function setCurrentPhpVersion(int $currentPhpVersion): void
    {
        $this->currentPhpVersion = $currentPhpVersion;
    }

    public function getTargetPhpVersion(): ?int
    {
        return $this->targetPhpVersion;
    }

    public function setTargetPhpVersion(?int $targetPhpVersion): void
    {
        $this->targetPhpVersion = $targetPhpVersion;
    }

    public function isReplied(): bool
    {
        return $this->isReplied;
    }

    public function setIsReplied(bool $isReplied): void
    {
        $this->isReplied = $isReplied;
    }
}
