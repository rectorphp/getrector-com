<?php

declare(strict_types=1);

namespace Rector\Website\Validator\Constraint;

use Doctrine\Common\Annotations\Annotation\Target;
use Rector\Website\Validator\PHPConstraintValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
final class PHPConstraint extends Constraint
{
    public function validatedBy(): string
    {
        return PHPConstraintValidator::class;
    }
}
