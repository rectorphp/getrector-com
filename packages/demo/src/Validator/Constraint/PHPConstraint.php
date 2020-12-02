<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Validator\Constraint;

use function Attribute;
use Attribute;
use Rector\Website\Demo\Validator\PHPConstraintValidator;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class PHPConstraint extends Constraint
{
    public function validatedBy(): string
    {
        return PHPConstraintValidator::class;
    }
}
