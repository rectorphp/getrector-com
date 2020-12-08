<?php

declare(strict_types=1);

namespace Rector\Website\Demo\ValueObjectFactory;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\Demo\ValueObject\DemoFormData;

final class RectorRunFactory
{
    public function create(string $config, DemoFormData $demoFormData): RectorRun
    {
        return new RectorRun(Uuid::uuid4(), new DateTimeImmutable(), $config, $demoFormData->getContent());
    }
}
