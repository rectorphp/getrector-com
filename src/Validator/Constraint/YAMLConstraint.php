<?php

declare(strict_types=1);

namespace Rector\Website\Validator\Constraint;

use Doctrine\Common\Annotations\Annotation\Target;
use Rector\Website\Validator\YAMLConstraintValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
final class YAMLConstraint extends Constraint
{
    public function validatedBy(): string
    {
        return YAMLConstraintValidator::class;
    }
}
