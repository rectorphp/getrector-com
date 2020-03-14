<?php

declare(strict_types=1);

namespace Rector\Website\ForCompanies\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Rector\Website\ForCompanies\ValueObject\EstimationConstant;

/**
 * @ORM\Entity
 */
class ProjectCalculation
{
    /**
     * In €
     * @var int
     */
    private const RECTOR_MONTHLY_EXPENSES = 14_500;

    /**
     * @var int
     */

    private const ROUNDING_LIMIT = 2;
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $inHouseMonths;

    /**
     * @ORM\Column(type="integer")
     */
    private int $inHouseMonthlyCosts;

    /**
     * @ORM\Column(type="integer")
     */
    private int $projectLinesOfCode;

    /**
     * @ORM\Column(type="integer")
     */
    private int $inHouseCosts;

    /**
     * @ORM\Column(type="float")
     */
    private float $rectorHours;

    public function __construct(int $inHouseMonths, int $inHouseMonthlyCosts, int $projectLinesOfCode)
    {
        $this->id = Uuid::uuid4();
        $this->inHouseMonths = $inHouseMonths;
        $this->inHouseMonthlyCosts = $inHouseMonthlyCosts;
        $this->projectLinesOfCode = $projectLinesOfCode;

        $this->inHouseCosts = $inHouseMonths * $inHouseMonthlyCosts;
        $this->rectorHours = $projectLinesOfCode * EstimationConstant::LINES_OF_CODE_TO_RECTOR_HOURS_CONSTANT;
    }

    public function getInHouseMonths(): int
    {
        return $this->inHouseMonths;
    }

    public function getInHouseMonthlyCosts(): int
    {
        return $this->inHouseMonthlyCosts;
    }

    public function getInHouseCosts(): int
    {
        return $this->inHouseCosts;
    }

    public function getRectorInMonths(): float
    {
        return round($this->rectorHours / 160, 1);
    }

    public function getRectorCosts(): float
    {
        return $this->getRectorInMonths() * self::RECTOR_MONTHLY_EXPENSES;
    }

    public function getRectorTimeEfficiency(): float
    {
        $efficiency = $this->getRectorInMonths() / $this->getInHouseMonths();

        return round($efficiency, self::ROUNDING_LIMIT);
    }

    public function getRectorPriceEfficiency(): float
    {
        $efficiency = $this->getRectorCosts() / $this->getInHouseCosts();

        return round($efficiency, self::ROUNDING_LIMIT);
    }

    public function getSavedMoneyAmount(): float
    {
        $amount = $this->inHouseCosts - $this->getRectorCosts();

        return round($amount, 2);
    }

    public function getReturnOfInvestment(): float
    {
        $roi = $this->getRectorCosts() / $this->getSavedMoneyAmount();

        return round($roi, 2);
    }

    public function getProjectLinesOfCode(): int
    {
        return $this->projectLinesOfCode;
    }
}
