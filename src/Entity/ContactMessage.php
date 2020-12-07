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
    public function __construct(
        /**
         * @ORM\Id
         * @ORM\Column(type="uuid")
         */
        private UuidInterface $id,
        /**
         * @ORM\Column(type="datetime_immutable")
         */
        private DateTimeImmutable $createdAt,
        /**
         * @ORM\Column(type="text")
         */
        private string $message,
    ) {
    }
}
