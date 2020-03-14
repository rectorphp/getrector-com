<?php

declare(strict_types=1);

namespace Rector\Website\Entity;

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
     * @ORM\Column()
     */
    private string $contactName;

    /**
     * @ORM\Column()
     */
    private string $projectAge;

    /**
     * @ORM\Column()
     */
    private string $contactEmail;

    /**
     * @ORM\Column()
     */
    private string $companyWeb;

    /**
     * @ORM\Column(type="text")
     */
    private string $improvementsSuggestions;

    /**
     * @ORM\Column(type="text")
     */
    private string $frustrationReasons;

    /**
     * @ORM\Column()
     */
    private string $frustrationLevel;

    /**
     * @ORM\Column()
     */
    private string $projectSize;

    /**
     * @ORM\Column()
     */
    private string $teamSize;

    /**
     * @ORM\Column()
     */
    private string $testCoverage;

    /**
     * @ORM\Column()
     */
    private string $continuousIntegration;

    /**
     * @ORM\Column()
     */
    private string $externalTools;

    /**
     * @ORM\Column()
     */
    private string $composerUpToDate;

    /**
     * @ORM\Column()
     */
    private string $usingComposer;

    /**
     * @ORM\Column()
     */
    private string $framework;

    /**
     * @ORM\Column()
     */
    private string $phpVersion;

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

    public function getPhpVersion(): ?string
    {
        return $this->phpVersion;
    }

    public function setPhpVersion(string $phpVersion): void
    {
        $this->phpVersion = $phpVersion;
    }

    public function getFramework(): ?string
    {
        return $this->framework;
    }

    public function setFramework(string $framework): void
    {
        $this->framework = $framework;
    }

    public function getUsingComposer(): ?string
    {
        return $this->usingComposer;
    }

    public function setUsingComposer(string $usingComposer): void
    {
        $this->usingComposer = $usingComposer;
    }

    public function getComposerUpToDate(): ?string
    {
        return $this->composerUpToDate;
    }

    public function setComposerUpToDate(string $composerUpToDate): void
    {
        $this->composerUpToDate = $composerUpToDate;
    }

    public function getExternalTools(): ?string
    {
        return $this->externalTools;
    }

    public function setExternalTools(string $externalTools): void
    {
        $this->externalTools = $externalTools;
    }

    public function getContinuousIntegration(): ?string
    {
        return $this->continuousIntegration;
    }

    public function setContinuousIntegration(string $continuousIntegration): void
    {
        $this->continuousIntegration = $continuousIntegration;
    }

    public function getTestCoverage(): ?string
    {
        return $this->testCoverage;
    }

    public function setTestCoverage(string $testCoverage): void
    {
        $this->testCoverage = $testCoverage;
    }

    public function getTeamSize(): ?string
    {
        return $this->teamSize;
    }

    public function setTeamSize(string $teamSize): void
    {
        $this->teamSize = $teamSize;
    }

    public function getProjectAge(): ?string
    {
        return $this->projectAge;
    }

    public function setProjectAge(string $projectAge): void
    {
        $this->projectAge = $projectAge;
    }

    public function getProjectSize(): ?string
    {
        return $this->projectSize;
    }

    public function setProjectSize(string $projectSize): void
    {
        $this->projectSize = $projectSize;
    }

    public function getFrustrationLevel(): ?string
    {
        return $this->frustrationLevel;
    }

    public function setFrustrationLevel(string $frustrationLevel): void
    {
        $this->frustrationLevel = $frustrationLevel;
    }

    public function getFrustrationReasons(): ?string
    {
        return $this->frustrationReasons;
    }

    public function setFrustrationReasons(string $frustrationReasons): void
    {
        $this->frustrationReasons = $frustrationReasons;
    }

    public function getImprovementsSuggestions(): ?string
    {
        return $this->improvementsSuggestions;
    }

    public function setImprovementsSuggestions(string $improvementsSuggestions): void
    {
        $this->improvementsSuggestions = $improvementsSuggestions;
    }

    public function getCompanyWeb(): ?string
    {
        return $this->companyWeb;
    }

    public function setCompanyWeb(string $companyWeb): void
    {
        $this->companyWeb = $companyWeb;
    }

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(string $contactEmail): void
    {
        $this->contactEmail = $contactEmail;
    }

    public function getContactName(): ?string
    {
        return $this->contactName;
    }

    public function setContactName(string $contactName): void
    {
        $this->contactName = $contactName;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getAnsweredAt(): DateTimeImmutable
    {
        return $this->answeredAt;
    }
}
