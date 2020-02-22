<?php declare (strict_types=1);

namespace Rector\Website\Entity;

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


    public function __construct(
        UuidInterface $id,
        string $phpVersion,
        string $framework,
        string $usingComposer,
        string $composerUpToDate,
        string $externalTools,
        string $continuousIntegration,
        string $testCoverage,
        string $teamSize,
        string $projectAge,
        string $projectSize,
        string $frustrationLevel,
        string $frustrationReasons,
        string $improvementsSuggestions,
        string $companyWeb,
        string $contactEmail,
        string $contactName
    )
    {
        $this->id = $id;
        $this->phpVersion = $phpVersion;
        $this->framework = $framework;
        $this->usingComposer = $usingComposer;
        $this->composerUpToDate = $composerUpToDate;
        $this->externalTools = $externalTools;
        $this->continuousIntegration = $continuousIntegration;
        $this->testCoverage = $testCoverage;
        $this->teamSize = $teamSize;
        $this->projectAge = $projectAge;
        $this->projectSize = $projectSize;
        $this->frustrationLevel = $frustrationLevel;
        $this->frustrationReasons = $frustrationReasons;
        $this->improvementsSuggestions = $improvementsSuggestions;
        $this->companyWeb = $companyWeb;
        $this->contactEmail = $contactEmail;
        $this->contactName = $contactName;
    }
}
