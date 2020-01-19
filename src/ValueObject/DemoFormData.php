<?php

declare(strict_types=1);

namespace Rector\Website\ValueObject;

use Rector\Website\Validator\Constraint\PHPConstraint;
use Rector\Website\Validator\Constraint\YamlConstraint;
use Symfony\Component\Validator\Constraints as Assert;

final class DemoFormData
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @PHPConstraint()
     */
    private $content;

    /**
     * @var string
     * @YamlConstraint()
     */
    private $config;

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
