<?php

declare(strict_types=1);

namespace Rector\Website\Repository;

use Rector\Website\Entity\RectorRun;
use Symfony\Component\Uid\Uuid;

/**
 * @extends AbstractRepository<RectorRun>
 */
final class RectorRunRepository extends AbstractRepository
{
    public function getTableFile(): string
    {
        return 'rector_runs.json';
    }

    /**
     * @param array<string, mixed> $row
     */
    public function createEntity(Uuid $uuid, array $row): RectorRun
    {
        return new RectorRun($uuid, $row['content'], $row['config'], $row['json_result'], $row['fatal_error_message']);
    }
}
