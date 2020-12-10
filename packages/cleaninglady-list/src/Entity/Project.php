<?php

declare(strict_types=1);

namespace Rector\Website\CleaningLadyList\Entity;

use DateTimeInterface;
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
    private ?Uuid $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private ?string $title = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private ?string $currentFramework = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private ?string $currentPhpVersion = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private ?string $desiredFramework = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private ?string $desiredPhpVersion = null;

    /**
     * @ORM\OneToMany(targetEntity=ProjectCheckbox::class, mappedBy="project")
     * @var Collection<int, ProjectCheckbox>|ProjectCheckbox[]
     */
    private array $projectCheckboxes;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->projectCheckboxes = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
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

    public function getDesiredFramework(): ?string
    {
        return $this->desiredFramework;
    }

    public function setDesiredFramework(string $desiredFramework): void
    {
        $this->desiredFramework = $desiredFramework;
    }

    public function getDesiredPhpVersion(): ?string
    {
        return $this->desiredPhpVersion;
    }

    public function setDesiredPhpVersion(string $desiredPhpVersion): void
    {
        $this->desiredPhpVersion = $desiredPhpVersion;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDate(DateTimeInterface $dateTime): void
    {
        $this->dateTime = $dateTime;
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
        if ($this->projectCheckboxes->contains($projectCheckbox)) {
            return;
        }

        $this->projectCheckboxes->add($projectCheckbox);
        $projectCheckbox->setProject($this);
    }
}
