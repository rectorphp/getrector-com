<?php

declare(strict_types=1);

namespace Rector\Website\CleaningLadyList\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity
 */
class Project implements TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $currentFramework;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $currentPhpVersion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private ?string $targetFramework = null;

    /**
     * @ORM\Column(type="integer")
     */
    private int $targetPhpVersion;

    /**
     * @ORM\OneToMany(targetEntity=ProjectCheckbox::class, mappedBy="project")
     * @var Collection<int, ProjectCheckbox>
     */
    private Collection $projectCheckboxes;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->projectCheckboxes = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCurrentFramework(): ?string
    {
        return $this->currentFramework;
    }

    public function setCurrentFramework(string $currentFramework): void
    {
        $this->currentFramework = $currentFramework;
    }

    public function getCurrentPhpVersion(): ?string
    {
        return $this->currentPhpVersion;
    }

    public function setCurrentPhpVersion(string $currentPhpVersion): void
    {
        $this->currentPhpVersion = $currentPhpVersion;
    }

    /**
     * @return Collection<int, ProjectCheckbox>
     */
    public function getProjectCheckboxes(): Collection
    {
        return $this->projectCheckboxes;
    }

    public function addProjectCheckbox(ProjectCheckbox $projectCheckbox): void
    {
        if ($this->projectCheckboxes->contains($projectCheckbox)) {
            return;
        }

        $this->projectCheckboxes->add($projectCheckbox);
        $projectCheckbox->setProject($this);
    }

    public function setTargetPhpVersion(int $targetPhpVersion): void
    {
        $this->targetPhpVersion = $targetPhpVersion;
    }

    public function getTargetPhpVersion(): int
    {
        return $this->targetPhpVersion;
    }

    public function getTargetFramework(): ?string
    {
        return $this->targetFramework;
    }

    public function setTargetFramework(?string $targetFramework): void
    {
        $this->targetFramework = $targetFramework;
    }
}
