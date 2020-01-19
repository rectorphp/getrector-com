<?php

declare(strict_types=1);

namespace Rector\Website\Validator\Constraint;

use Doctrine\Common\Annotations\Annotation\Target;
use Rector\Website\Validator\YamlConstraintValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
final class YamlConstraint extends Constraint
{
    /**
     * @var string
     */
    public $message = 'The string "{{ string }}" is not valid YAML';

    public function validatedBy(): string
    {
        return YamlConstraintValidator::class;
    }
}
