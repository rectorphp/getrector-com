<?php

declare(strict_types=1);

namespace Rector\Website\ValueObject;

final class RectorRunValueObject
{
    /**
     * @var string|null
     */
    private $content;

    /**
     * @var string|null
     */
    private $set;

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getSet(): ?string
    {
        return $this->set;
    }

    public function setSet(string $set): void
    {
        $this->set = $set;
    }
}
