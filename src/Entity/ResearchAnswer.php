<?php declare (strict_types=1);

namespace Rector\Website\Entity;

use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 */
class ResearchAnswer
{
    /**
     * @var UuidInterface
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private $id;

    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }
}
