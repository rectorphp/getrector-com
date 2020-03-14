<?php

declare(strict_types=1);

namespace Rector\Website\ForCompanies\ValueObject;

final class ProjectCalculationFormData
{
    /**
     * @var int
     */
    private $inHouseMonths;

    /**
     * @var int
     */
    private $inHouseMonthlyCosts;

    /**
     * @var int
     */
    private $projectLinesOfCode;

    public function __construct(int $inHouseMonths, int $inHouseMonthlyCosts, int $projectLinesOfCode)
    {
        $this->inHouseMonths = $inHouseMonths;
        $this->inHouseMonthlyCosts = $inHouseMonthlyCosts;
        $this->projectLinesOfCode = $projectLinesOfCode;
    }

    public function getInHouseMonths(): int
    {
        return $this->inHouseMonths;
    }

    public function setInHouseMonths(int $inHouseMonths): void
    {
        $this->inHouseMonths = $inHouseMonths;
    }

    public function getInHouseMonthlyCosts(): int
    {
        return $this->inHouseMonthlyCosts;
    }

    public function setInHouseMonthlyCosts(int $inHouseMonthlyCosts): void
    {
        $this->inHouseMonthlyCosts = $inHouseMonthlyCosts;
    }

    public function getProjectLinesOfCode(): int
    {
        return $this->projectLinesOfCode;
    }

    public function setProjectLinesOfCode(int $projectLinesOfCode): void
    {
        $this->projectLinesOfCode = $projectLinesOfCode;
    }
}
