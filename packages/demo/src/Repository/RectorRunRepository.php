<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Rector\Website\Demo\Entity\RectorRun;
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

    public function get(UuidInterface $uuid): RectorRun
    {
        $rectorRun = $this->entityManager->find(RectorRun::class, $uuid);
        if ($rectorRun instanceof RectorRun) {
            return $rectorRun;
        }

        throw new NotFoundHttpException();
    }
}
