<?php

declare(strict_types=1);

namespace Rector\Website\Demo\ValueObject;

use Rector\Website\Demo\Validator\Constraint\PHPConstraint;

final class DemoFormData
{
    public function __construct(
        #[PHPConstraint]
        private string $content,
        #[PHPConstraint]
        private string $config,
    ) {
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getConfig(): string
    {
        return $this->config;
    }

    public function setConfig(string $config): void
    {
        $this->config = $config;
    }
}
