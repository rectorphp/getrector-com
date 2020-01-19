<?php

declare(strict_types=1);

namespace Rector\Website\Form;

final class RectorRunFormData
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $config;

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
