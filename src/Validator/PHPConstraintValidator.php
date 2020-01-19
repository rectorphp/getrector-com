<?php

declare(strict_types=1);

namespace Rector\Website\Validator;

use Rector\Website\Exception\Linter\MissingPHPOpeningTagException;
use Rector\Website\Exception\LintingException;
use Rector\Website\Lint\PHPFileLinter;
use Rector\Website\Validator\Constraint\PHPConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @see https://symfony.com/doc/current/validation/custom_constraint.html#creating-the-validator-itself
 */
final class PHPConstraintValidator extends ConstraintValidator
{
    /**
     * @var PHPFileLinter
     */
    private $phpFileLinter;

    public function __construct(PHPFileLinter $phpFileLinter)
    {
        $this->phpFileLinter = $phpFileLinter;
    }

    /**
     * @param string $value
     * @param PHPConstraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        try {
            $this->phpFileLinter->checkContentSyntax($value);
        } catch (MissingPHPOpeningTagException $missingPHPOpeningTagException) {
            $this->context->buildViolation('Add opening "<?php" tag')
                ->addViolation();
        } catch (LintingException $lintingException) {
            $this->context->buildViolation('Value "%string%" is not a valid PHP: %error%')
                ->setParameter('%string%', $value)
                ->setParameter('%error%', $lintingException->getMessage())
                ->addViolation();
        }
    }
}
