<?php

declare(strict_types=1);

namespace Rector\Website\CleaningLadyList\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Rector\Website\CleaningLadyList\Entity\ProjectCheckbox;
use Rector\Website\Exception\ShouldNotHappenException;

final class ProjectCheckboxRepository
{
    /**
     * @var ObjectRepository<ProjectCheckbox>
     */
    private ObjectRepository $objectRepository;

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        $this->objectRepository = $this->entityManager->getRepository(ProjectCheckbox::class);
    }

    public function get(int $projectCheckboxId): ProjectCheckbox
    {
        $projectCheckbox = $this->objectRepository->find($projectCheckboxId);
        if ($projectCheckbox === null) {
            throw new ShouldNotHappenException();
        }

        return $projectCheckbox;
    }

    public function save(ProjectCheckbox $projectCheckbox): void
    {
        $this->entityManager->persist($projectCheckbox);
        $this->entityManager->flush();
    }
}
