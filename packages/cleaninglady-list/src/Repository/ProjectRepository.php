<?php

declare(strict_types=1);

namespace Rector\Website\CleaningLadyList\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use Rector\Website\CleaningLadyList\Entity\Project;

final class ProjectRepository
{
    /**
     * @var EntityRepository<Project>
     */
    private ObjectRepository $entityRepository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->entityRepository = $entityManager->getRepository(Project::class);
    }

    public function save(Project $project): void
    {
        $this->entityManager->persist($project);
        $this->entityManager->flush();
    }

    /**
     * @return Project[]
     */
    public function getProjects(): array
    {
        return $this->entityRepository->findAll();
    }
}
