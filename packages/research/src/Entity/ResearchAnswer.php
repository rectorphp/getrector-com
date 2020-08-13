<?php

declare(strict_types=1);

namespace Rector\Website\Research\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 */
class ResearchAnswer
{
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $answeredAt;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->answeredAt = new DateTimeImmutable();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }
}
