<?php

declare(strict_types=1);

namespace App\RuleFilter\ValueObject;

/**
 * @api used in blade
 */
final readonly class ConfiguredDiffSample
{
    public function __construct(
        private string $diffCodeSample,
        private string $configuration
    ) {
    }

    public function getDiffCodeSample(): string
    {
        return $this->diffCodeSample;
    }

    public function getConfiguration(): string
    {
        return $this->configuration;
    }
}
