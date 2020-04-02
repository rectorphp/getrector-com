<?php

declare(strict_types=1);

namespace Rector\Website\Research\ValueObject;

final class ResearchFormData
{
    public string $phpVersion;

    public string $framework;

    public string $usingComposer;

    public string $composerUpToDate;

    public string $externalTools;

    public string $continuousIntegration;

    public string $testCoverage;

    public string $teamSize;

    public string $projectAge;

    public string $projectSize;

    public string $frustrationLevel;

    public string $frustrationReasons;

    public string $improvementsSuggestions;

    public string $companyWeb;

    public string $contactEmail;

    public string $contactName;
}
