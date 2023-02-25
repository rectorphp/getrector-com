<?php

declare(strict_types=1);

namespace Rector\Website\Repository;

use Jajo\JSONDB;
use Nette\Utils\FileSystem;
use Rector\Website\Entity\RectorRun;
use Symfony\Component\Uid\Uuid;

final class RectorRunRepository
{
    /**
     * @var string
     */
    private const TABLE_FILE = 'rector_runs.json';

    // @see https://github.com/donjajo/php-jsondb
    private readonly JSONDB $jsonDb;

    public function __construct()
    {
        $storageDirectory = __DIR__ . '/../../../resources/json-database';
        $this->jsonDb = new JSONDB($storageDirectory);

        $repositoryStorageFile = $storageDirectory . '/' . self::TABLE_FILE;

        // create empty file on boot
        if (! file_exists($repositoryStorageFile)) {
            FileSystem::createDir(dirname($repositoryStorageFile));
            file_put_contents($repositoryStorageFile, '[]');
        }
    }

    public function save(RectorRun $rectorRun): void
    {
        $this->jsonDb->insert(self::TABLE_FILE, $rectorRun->jsonSerialize());
    }

    public function get(Uuid $uuid): RectorRun|null
    {
        $rows = $this->jsonDb->select('*')
            ->from(self::TABLE_FILE)
            ->where([
                'uuid' => $uuid->jsonSerialize(),
            ])
            ->get();

        if ($rows === []) {
            return null;
        }

        $row = $rows[0];

        $uuid = Uuid::fromString($row['uuid']);

        return new RectorRun($uuid, $row['content'], $row['config'], $row['json_result'], $row['fatal_error_message']);
    }
}
