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
    public Uuid $id;
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
     * @ORM\Column(type="integer")
     */
    public int $projectSize;

    /**
     * @ORM\Column(type="string")
     */
    public string $framework;

    /**
     * @ORM\Column(type="integer")
     */
    public int $currentPhpVersion;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public ?int $targetPhpVersion = null;
}
