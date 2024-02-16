<?php

declare(strict_types=1);

namespace Rector\Website\Contract;

use JsonSerializable;
use Symfony\Component\Uid\Uuid;

/**
 * @template TEntity as JsonSerializable
 */
interface RepositoryInterface
{
    public function getTableFile(): string;

    /**
     * @param array<string, mixed> $row
     * @return TEntity
     */
    public function createEntity(Uuid $uuid, array $row): JsonSerializable;
}
