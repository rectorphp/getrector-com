<?php

declare(strict_types=1);

namespace Rector\Website\Repository;

use Rector\Website\Entity\AstRun;
use Symfony\Component\Uid\Uuid;

/**
 * @extends AbstractRepository<AstRun>
 */
final class AstRunRepository extends AbstractRepository
{
    public function getTableFile(): string
    {
        return 'ast_runs.json';
    }

    /**
     * @param array<string, mixed> $row
     */
    public function createEntity(Uuid $uuid, array $row): AstRun
    {
        return new AstRun($uuid, $row['content']);
    }
}
