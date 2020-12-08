<?php

declare(strict_types=1);

namespace Rector\Website\Demo\ValueObject;

final class DemoLink
{
    public function __construct(private string $label, private string $uuid, )
    {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
