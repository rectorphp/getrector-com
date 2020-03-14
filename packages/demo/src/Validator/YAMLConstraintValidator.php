<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Validator;

use Rector\Website\Demo\Exception\LintingException;
use Rector\Website\Demo\Lint\YamlLinter;
use Rector\Website\Demo\Validator\Constraint\YAMLConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * This is the only way to create per-property callback validation.
 * It is needed for per-input error render in a form.
 *
 * @see https://symfony.com/doc/current/validation/custom_constraint.html#creating-the-validator-itself
 * @see \Rector\Website\Demo\Validator\Constraint\YamlConstraint
 */
final class YAMLConstraintValidator extends ConstraintValidator
{
    /**
     * @var YamlLinter
     */
    private $yamlLinter;

    public function __construct(YamlLinter $yamlLinter)
    {
        $this->yamlLinter = $yamlLinter;
    }

    /**
     * @param string $value
     * @param YamlConstraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        try {
            $this->yamlLinter->checkContentSyntax($value);
        } catch (LintingException $lintingException) {
            $this->context->buildViolation('Fix YAML syntax: %error%')
                ->setParameter('%error%', $lintingException->getMessage())
                ->addViolation();
        }
    }
}
