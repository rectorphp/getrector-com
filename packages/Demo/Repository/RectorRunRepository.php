<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Repository;

use Jajo\JSONDB;
use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\Demo\Exception\EntityNotFoundException;
use Symfony\Component\Uid\Uuid;

final class RectorRunRepository
{
    /**
     * @var string
     */
    private const TABLE_FILE = 'rector_runs.json';

    public function __construct(
        // @see https://github.com/donjajo/php-jsondb
        private JSONDB $jsonDb
    ) {
    }

    public function save(RectorRun $rectorRun): void
    {
        $this->jsonDb->insert(self::TABLE_FILE, $rectorRun->jsonSerialize());
    }

    public function get(Uuid $uuid): RectorRun
    {
        $rows = $this->jsonDb->select('*')
            ->from(self::TABLE_FILE)
            ->where([
                'uuid' => $uuid->jsonSerialize(),
            ])
            ->get();

        if ($rows === null) {
            $errorMessage = sprintf('Rector run was not found for "%s"', $uuid->__toString());
            throw new EntityNotFoundException($errorMessage);
        }

        $row = $rows[0];

        $uuid = Uuid::fromString($row['uuid']);

        return new RectorRun($uuid, $row['content'], $row['config'], $row['json_result'], $row['fatal_error_message']);
    }
}
