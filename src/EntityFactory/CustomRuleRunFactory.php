<?php

declare(strict_types=1);

namespace Rector\Website\EntityFactory;

use Rector\Website\Entity\CustomRuleRun;
use Symfony\Component\Uid\Uuid;

final class CustomRuleRunFactory
{
    public function createEmpty(): CustomRuleRun
    {
        return new CustomRuleRun(Uuid::v4(), 'custom rule', 'PHP code to change');
    }
}
