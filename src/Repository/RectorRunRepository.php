<?php

declare(strict_types=1);

namespace Rector\Website\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Rector\Website\Entity\RectorRun;

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
}
