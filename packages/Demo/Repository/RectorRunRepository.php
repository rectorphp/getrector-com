<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Repository;

use Rector\Website\Demo\Entity\RectorRun;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

final class RectorRunRepository
{
<<<<<<< HEAD
<<<<<<< HEAD
=======
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

>>>>>>> enable parallel on PHP 8.1
    public function save(RectorRun $rectorRun): void
    {
        // @todo save
=======
    public function save(RectorRun $rectorRun): void
    {
        // @todo
>>>>>>> remove csrf and doctrine from demo
    }

    public function get(Uuid $uuid): never
    {
<<<<<<< HEAD
        // @todo fetch
=======
        // @todo
>>>>>>> remove csrf and doctrine from demo
        throw new NotFoundHttpException();
    }
}
