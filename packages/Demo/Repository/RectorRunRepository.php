<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Rector\Website\Demo\Entity\RectorRun;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

final class RectorRunRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(RectorRun $rectorRun): void
    {
        $this->entityManager->persist($rectorRun);
        $this->entityManager->flush();
    }

    public function get(Uuid $uuid): RectorRun
    {
        $rectorRun = $this->entityManager->find(RectorRun::class, $uuid);
        if ($rectorRun instanceof RectorRun) {
            return $rectorRun;
        }

        throw new NotFoundHttpException();
    }
}
