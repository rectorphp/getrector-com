<?php

declare(strict_types=1);

namespace Rector\Website\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 */
final class RectorRun
{
    // @todo add timestampable to know when what


    /**
     * @ORM\Column(type="text")
     * @var string|null
     */
    private $content;

    /**
     * @ORM\Column(type="string")
     * @var string|null
     */
    private $setName;

    /**
     * @ORM\Column(type="text")
     * @var string|null
     */
    private $resultJson;

    /**
     * @var UuidInterface|null
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @ORM\Column(type="uuid", unique=true)
     */
    private $uuid;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    public function getUuid(): ?UuidInterface
    {
        return $this->uuid;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getSetName(): ?string
    {
        return $this->setName;
    }

    public function setSetName(?string $setName): void
    {
        $this->setName = $setName;
    }

    public function setResultJson(?string $resultJson): void
    {
        $this->resultJson = $resultJson;
    }
}
