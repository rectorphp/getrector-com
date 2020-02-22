<?php declare (strict_types=1);

namespace Rector\Website\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Doctrine\ORM\Mapping as ORM;

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

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $answeredAt;

    /**
     * @var string
     * @ORM\Column()
     */
    private $phpVersion;

    /**
     * @var string
     * @ORM\Column()
     */
    private $framework;

    /**
     * @var string
     * @ORM\Column()
     */
    private $usingComposer;

    /**
     * @var string
     * @ORM\Column()
     */
    private $composerUpToDate;

    /**
     * @var string
     * @ORM\Column()
     */
    private $externalTools;

    /**
     * @var string
     * @ORM\Column()
     */
    private $continuousIntegration;

    /**
     * @var string
     * @ORM\Column()
     */
    private $testCoverage;

    /**
     * @var string
     * @ORM\Column()
     */
    private $teamSize;

    /**
     * @var string
     * @ORM\Column()
     */
    private $projectAge;

    /**
     * @var string
     * @ORM\Column()
     */
    private $projectSize;

    /**
     * @var string
     * @ORM\Column()
     */
    private $frustrationLevel;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $frustrationReasons;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $improvementsSuggestions;

    /**
     * @var string
     * @ORM\Column()
     */
    private $companyWeb;

    /**
     * @var string
     * @ORM\Column()
     */
    private $contactEmail;

    /**
     * @var string
     * @ORM\Column()
     */
    private $contactName;


    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->answeredAt = new \DateTimeImmutable();
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
}
