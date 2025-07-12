<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\RectorRun;
use Jajo\JSONDB;
use Nette\Utils\FileSystem;
use Symfony\Component\Uid\Uuid;

final readonly class RectorRunRepository
{
    private const string TABLE_FILE = 'rector_runs.json';

    // @see https://github.com/donjajo/php-jsondb
    private JSONDB $jsondb;

    public function __construct()
    {
        $storageDirectory = __DIR__ . '/../../resources/json-database';
        $this->jsondb = new JSONDB($storageDirectory);

        $repositoryStorageFile = $storageDirectory . '/' . self::TABLE_FILE;

        // create empty file on boot
        if (! file_exists($repositoryStorageFile)) {
            FileSystem::createDir(dirname($repositoryStorageFile));
            file_put_contents($repositoryStorageFile, '[]');
        }
    }

    public function save(RectorRun $rectorRun): void
    {
        $this->jsondb->insert(self::TABLE_FILE, $rectorRun->jsonSerialize());
    }

    public function get(Uuid|string $uuid): RectorRun|null
    {
        // be tolerant to string
        if (! $uuid instanceof Uuid) {
            $uuid = Uuid::fromString($uuid);
        }

        $rows = $this->jsondb->select()
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

        return $this->createEntity($uuid, $row);
    }

    /**
     * @param array<string, mixed> $row
     */
    private function createEntity(Uuid $uuid, array $row): RectorRun
    {
        return new RectorRun($uuid, $row['content'], $row['config'], $row['json_result'], $row['fatal_error_message']);
    }
}
