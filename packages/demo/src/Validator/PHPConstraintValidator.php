<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Validator;

use Nette\Utils\Strings;
use Rector\Website\Demo\Exception\Linter\MissingPHPOpeningTagException;
use Rector\Website\Demo\Exception\LintingException;
use Rector\Website\Demo\Lint\PHPLinter;
use Rector\Website\Demo\Validator\Constraint\PHPConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @see https://symfony.com/doc/current/validation/custom_constraint.html#creating-the-validator-itself
 *
 * @see \Rector\Website\Demo\Tests\Validator\PHPConstraintValidatorTest
 */
final class PHPConstraintValidator extends ConstraintValidator
{
    /**
     * @see https://regex101.com/r/Y1Qmks/1
     * @var string
     */
    private const PHP_PARSE_ERROR_REGEX = '#PHP Parse error\:\s+syntax error\,\s+#';

    public function __construct(
        private PHPLinter $phpLinter
    ) {
    }

    /**
     * @param string $value
     * @param PHPConstraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        try {
            $this->phpLinter->checkContentSyntax($value);
        } catch (MissingPHPOpeningTagException) {
            $constraintViolation = $this->context->buildViolation('Add opening "<?php" tag');
            $constraintViolation->addViolation();
        } catch (LintingException $lintingException) {
            $usefulLinterMessage = $this->clearLinterMessage($lintingException->getMessage());

            $constraintViolation = $this->context->buildViolation('Fix PHP syntax: %error%');
            $constraintViolation->setParameter('%error%', $usefulLinterMessage);
            $constraintViolation->addViolation();
        }
    }

    /**
     * Remove useless "error" name. We know it's an error.
     */
    private function clearLinterMessage(string $message): string
    {
        return Strings::replace($message, self::PHP_PARSE_ERROR_REGEX, '');
    }
}
