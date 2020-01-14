<?php

declare(strict_types=1);

namespace Rector\Website\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

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
     * @ORM\Column(type="string")
     * @var string
     */
    private $contentHash;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $setName;

    /**
     * @ORM\Column(type="text")
     * @var string|null
     */
    private $resultJson;

    /**
     * @var UuidInterface
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    public function __construct(UuidInterface $id, string $setName, string $content)
    {
        $this->id = $id;
        $this->setName = $setName;
        $this->content = $content;
        $this->contentHash = $this->calculateContentHash($content);
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }


    public function getSetName(): string
    {
        return $this->setName;
    }

    public function getContentHash(): string
    {
        return $this->contentHash;
    }

    private function calculateContentHash(string $content): string
    {
        return hash('sha256', $content);
    }
}
