<?php

declare(strict_types=1);

namespace Rector\Website\CleaningLadyList\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Checkbox
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
     * @var string
     */
    private ?string $task = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private ?string $category = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $framework = null;

    /**
     * @ORM\ManyToMany(targetEntity=ProjectCheckbox::class, mappedBy="checkboxes")
     * @var Collection<int, ProjectCheckbox>|ProjectCheckbox[]
     */
    private Collection $projectCheckboxes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $how = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $why = null;

    public function __construct()
    {
        $this->projectCheckboxes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTask(): ?string
    {
        return $this->task;
    }

    public function setTask(string $task): void
    {
        $this->task = $task;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function getFramework(): ?string
    {
        return $this->framework;
    }

    public function setFramework(?string $framework): void
    {
        $this->framework = $framework;
    }

    /**
     * @return Collection<int, ProjectCheckbox>|ProjectCheckbox[]
     */
    public function getProjectCheckboxes(): Collection
    {
        return $this->projectCheckboxes;
    }

    public function addProjectCheckbox(ProjectCheckbox $projectCheckbox): void
    {
        if (! $this->projectCheckboxes->contains($projectCheckbox)) {
            $this->projectCheckboxes->add($projectCheckbox);
            $projectCheckbox->addCheckbox($this);
        }
    }

    public function removeProjectCheckbox(ProjectCheckbox $projectCheckbox): void
    {
        if ($this->projectCheckboxes->contains($projectCheckbox)) {
            $this->projectCheckboxes->removeElement($projectCheckbox);
            $projectCheckbox->removeCheckbox($this);
        }
    }

    public function getHow(): ?string
    {
        return $this->how;
    }

    public function setHow(?string $how): void
    {
        $this->how = $how;
    }

    public function getWhy(): ?string
    {
        return $this->why;
    }

    public function setWhy(?string $why): void
    {
        $this->why = $why;
    }
}
