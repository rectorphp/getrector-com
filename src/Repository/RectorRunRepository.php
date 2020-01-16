<?php

declare(strict_types=1);

namespace Rector\Website\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Ramsey\Uuid\UuidInterface;
use Rector\Website\Entity\RectorRun;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class RectorRunRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(RectorRun $rectorRun): void
    {
        $this->entityManager->persist($rectorRun);
        $this->entityManager->flush();
    }


    public function findMostRecentSetRun(string $setName, string $contentHash): ?RectorRun
    {
        try {
            return $this->entityManager->createQueryBuilder()
                ->from(RectorRun::class, 'run')
                ->select('run')
                ->where('run.contentHash = :contentHash AND run.setName = :setName')
                ->setParameters([
                    'setName' => $setName,
                    'contentHash' => $contentHash,
                ])
                ->orderBy('run.executedAt', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $exception) {
            return null;
        }
    }

    public function get(UuidInterface $id): RectorRun
    {
        $rectorRun = $this->entityManager->find(RectorRun::class, $id);

        if (!$rectorRun) {
            throw new NotFoundHttpException();
        }

        return $rectorRun;
    }
}
