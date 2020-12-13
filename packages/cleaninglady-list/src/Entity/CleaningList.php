<?php

declare(strict_types=1);

namespace Rector\Website\CleaningLadyList\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class CleaningList
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $name = null;

    /**
     * @ORM\OneToMany(targetEntity="Rector\Website\CleaningLadyList\Entity\Checkbox", mappedBy="cleaningList")
     * @var Collection<CleaningList>
     */
    private Collection $checkboxes;

    public function __construct()
    {
        $this->checkboxes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
