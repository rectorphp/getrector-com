<?php

declare(strict_types=1);

namespace Rector\Website\Validator;

use Nette\Utils\Strings;
use Rector\Website\Exception\Linter\MissingPHPOpeningTagException;
use Rector\Website\Exception\LintingException;
use Rector\Website\Lint\PHPLinter;
use Rector\Website\Validator\Constraint\PHPConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @see https://symfony.com/doc/current/validation/custom_constraint.html#creating-the-validator-itself
 */
final class PHPConstraintValidator extends ConstraintValidator
{
    /**
     * @var PHPLinter
     */
    private $phpLinter;

    public function __construct(PHPLinter $phpLinter)
    {
        $this->phpLinter = $phpLinter;
    }

    /**
     * @param string $value
     * @param PHPConstraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        try {
            $this->phpLinter->checkContentSyntax($value);
        } catch (MissingPHPOpeningTagException $missingPHPOpeningTagException) {
            $this->context->buildViolation('Add opening "<?php" tag')
                ->addViolation();
        } catch (LintingException $lintingException) {
            $usefulLinterMessage = $this->clearLinterMessage($lintingException->getMessage());

            $this->context->buildViolation('Fix PHP syntax: %error%')
                ->setParameter('%error%', $usefulLinterMessage)
                ->addViolation();
        }
    }

    /**
     * Remove useless "error" name. We know it's an error.
     */
    private function clearLinterMessage(string $message): string
    {
        return Strings::replace($message, '#PHP Parse error\:\s+syntax error\,\s+#');
    }
}
