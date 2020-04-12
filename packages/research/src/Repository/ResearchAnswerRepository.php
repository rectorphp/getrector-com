<?php

declare(strict_types=1);

namespace Rector\Website\Research\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Rector\Website\Research\Entity\ResearchAnswer;

final class ResearchAnswerRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(ResearchAnswer $researchAnswer): void
    {
        $this->entityManager->persist($researchAnswer);
        $this->entityManager->flush();
    }
}
