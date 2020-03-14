<?php

declare(strict_types=1);

namespace Rector\Website\Demo\ValueObject;

use Rector\Website\Demo\Validator\Constraint\PHPConstraint;
use Rector\Website\Demo\Validator\Constraint\YAMLConstraint;

final class DemoFormData
{
    /**
     * @PHPConstraint()
     */
    private string $content;

    /**
     * @YAMLConstraint()
     */
    private string $config;

    public function __construct(string $content, string $config)
    {
        $this->content = $content;
        $this->config = $config;
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
