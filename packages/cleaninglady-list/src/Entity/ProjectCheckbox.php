<?php

declare(strict_types=1);

namespace Rector\Website\CleaningLadyList\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\DateTime;

/**
 * @ORM\Entity()
 */
class ProjectCheckbox
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="projectCheckboxes")
     */
    private ?Project $project = null;

    /**
     * @ORM\ManyToMany(targetEntity=Checkbox::class, inversedBy="projectCheckboxes")
     * @var Collection<int, Checkbox>|Checkbox[]
     */
    private array $checkboxes;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $completedAt = null;

    public function __construct()
    {
        $this->checkboxes = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(Project $project): void
    {
        $this->project = $project;
    }

    /**
     * @return Collection<int, Checkbox>|Checkbox[]
     */
    public function getCheckboxes(): Collection
    {
        return $this->checkboxes;
    }

    public function addCheckbox(Checkbox $checkbox): void
    {
        if (! $this->checkboxes->contains($checkbox)) {
            $this->checkboxes->add($checkbox);
        }
    }

    public function removeCheckbox(Checkbox $checkbox): void
    {
        if ($this->checkboxes->contains($checkbox)) {
            $this->checkboxes->removeElement($checkbox);
        }
    }

    public function inverseCompleteAt(): void
    {
        if ($this->completedAt === null) {
            $this->completedAt = DateTime::from('now');
            return;
        }

        $this->completedAt = null;
    }

    public function getCompleteAt(): ?DateTimeInterface
    {
        return $this->completedAt ?? null;
    }

    public function getCompleteAtAsString(): string
    {
        if ($this->completedAt !== null) {
            return $this->completedAt->format('d.m.y');
        }

        return '';
    }
}
