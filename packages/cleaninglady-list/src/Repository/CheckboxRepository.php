<?php

declare(strict_types=1);

namespace Rector\Website\CleaningLadyList\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use Rector\Website\CleaningLadyList\Entity\Checkbox;

final class CheckboxRepository
{
    /**
     * @var EntityRepository<Checkbox>
     */
    private ObjectRepository $entityRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityRepository = $entityManager->getRepository(Checkbox::class);
    }

    /**
     * @return Checkbox[]
     */
    public function findByFramework(?string $framework): array
    {
        return $this->entityRepository->createQueryBuilder('c')
            ->where('c.framework = :framework')
            ->setParameter('framework', $framework)
            ->orWhere('c.framework is NULL')
            ->getQuery()
            ->getResult();
    }
}
