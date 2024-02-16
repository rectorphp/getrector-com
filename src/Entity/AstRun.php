<?php

declare(strict_types=1);

namespace Rector\Website\Entity;

use JsonSerializable;
use Symfony\Component\Uid\Uuid;

final class AstRun implements JsonSerializable
{
    public function __construct(
        private readonly Uuid $uuid,
        private readonly string $content,
    ) {
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid->jsonSerialize(),
            'content' => $this->content,
        ];
    }

    public function hasRun(): bool
    {
        return $this->content !== '';
    }
}
