<?php

declare(strict_types=1);

namespace Rector\Website\Demo\ValueObjectFactory;

use DateTimeImmutable;
use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\Demo\ValueObject\DemoFormData;
use Symfony\Component\Uid\Uuid;

final class RectorRunFactory
{
    public function create(string $config, DemoFormData $demoFormData): RectorRun
    {
        return new RectorRun(Uuid::v4(), new DateTimeImmutable(), $config, $demoFormData->getContent());
    }
}
