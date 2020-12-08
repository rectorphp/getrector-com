<?php

declare(strict_types=1);

namespace Rector\Website\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 */
class ContactMessage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    public UuidInterface $id;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    public DateTimeImmutable $createdAt;
    /**
     * @ORM\Column(type="text")
     */
    public string $message;

    /**
     * @ORM\Column(type="string")
     */
    public string $name;

    /**
     * @ORM\Column(type="string")
     */
    public string $email;

    /**
     * @ORM\Column(type="int")
     */
    public int $projectSize;

    /**
     * @ORM\Column(type="string")
     */
    public string $framework;

    /**
     * @ORM\Column(type="int")
     */
    public int $currentPhpVersion;

    /**
     * @ORM\Column(type="int")
     */
    public ?int $targetPhpVersion;
}
