<?php

declare(strict_types=1);

namespace Rector\Website\Repository;

use Jajo\JSONDB;
use JsonSerializable;
use Nette\Utils\FileSystem;
use Rector\Website\Contract\RepositoryInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @template TEntity of JsonSerializable
 * @implements RepositoryInterface<TEntity>
 */
abstract class AbstractRepository implements RepositoryInterface
{
    // @see https://github.com/donjajo/php-jsondb
    private readonly JSONDB $jsonDb;

    public function __construct()
    {
        $storageDirectory = __DIR__ . '/../../resources/json-database';
        $this->jsonDb = new JSONDB($storageDirectory);

        $repositoryStorageFile = $storageDirectory . '/' . $this->getTableFile();

        // create empty file on boot
        if (! file_exists($repositoryStorageFile)) {
            FileSystem::createDir(dirname($repositoryStorageFile));
            file_put_contents($repositoryStorageFile, '[]');
        }
    }

    /**
     * @param TEntity $entity
     */
    public function save(object $entity): void
    {
        $this->jsonDb->insert($this->getTableFile(), $entity->jsonSerialize());
    }

    /**
     * @return TEntity|null
     */
    public function get(Uuid|string $uuid): object|null
    {
        // be tolerant to string
        if (! $uuid instanceof Uuid) {
            $uuid = Uuid::fromString($uuid);
        }

        $rows = $this->jsonDb->select()
            ->from($this->getTableFile())
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
}
