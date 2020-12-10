<?php

declare(strict_types=1);

namespace Rector\Website\CleaningLadyList\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Rector\Website\CleaningLadyList\Entity\Project;

final class ProjectRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function save(Project $project): void
    {
        $this->entityManager->persist($project);
        $this->entityManager->flush();
    }
}
