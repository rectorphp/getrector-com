<?php

declare(strict_types=1);

namespace Rector\Website\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Rector\Website\Entity\ResearchAnswer;

final class ResearchAnswerRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

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
