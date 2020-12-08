<?php

declare(strict_types=1);

namespace Rector\Website\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Rector\Website\Entity\ContactMessage;

final class ContactMessageRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(ContactMessage $contactMessage): void
    {
        $this->entityManager->persist($contactMessage);
        $this->entityManager->flush();
    }
}
