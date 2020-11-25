<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Validator;

use Nette\Utils\Strings;
use Rector\Website\Demo\Exception\ForbiddenPHPFunctionException;
use Rector\Website\Demo\Exception\Linter\MissingPHPOpeningTagException;
use Rector\Website\Demo\Exception\LintingException;
use Rector\Website\Demo\Lint\ForbiddenPHPFunctionsChecker;
use Rector\Website\Demo\Lint\PHPLinter;
use Rector\Website\Demo\Validator\Constraint\PHPConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @see https://symfony.com/doc/current/validation/custom_constraint.html#creating-the-validator-itself
 */
final class PHPConstraintValidator extends ConstraintValidator
{
    /**
     * @see https://regex101.com/r/Y1Qmks/1
     * @var string
     */
    private const PHP_PARSE_ERROR_REGEX = '#PHP Parse error\:\s+syntax error\,\s+#';

    private PHPLinter $phpLinter;

    private ForbiddenPHPFunctionsChecker $forbiddenPHPFunctionsChecker;

    public function __construct(PHPLinter $phpLinter, ForbiddenPHPFunctionsChecker $forbiddenPHPFunctionsChecker)
    {
        $this->phpLinter = $phpLinter;
        $this->forbiddenPHPFunctionsChecker = $forbiddenPHPFunctionsChecker;
    }

    /**
     * @param string $value
     * @param PHPConstraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        try {
            $this->phpLinter->checkContentSyntax($value);
            $this->forbiddenPHPFunctionsChecker->checkCode($value);
        } catch (MissingPHPOpeningTagException $missingphpOpeningTagException) {
            $violation = $this->context->buildViolation('Add opening "<?php" tag');
            $violation->addViolation();
        } catch (ForbiddenPHPFunctionException $forbiddenphpFunctionException) {
            $violation = $this->context->buildViolation($forbiddenphpFunctionException->getMessage());
            $violation->addViolation();
        } catch (LintingException $lintingException) {
            $usefulLinterMessage = $this->clearLinterMessage($lintingException->getMessage());

            $violation = $this->context->buildViolation('Fix PHP syntax: %error%');
            $violation->setParameter('%error%', $usefulLinterMessage);
            $violation->addViolation();
        }
    }

    /**
     * Remove useless "error" name. We know it's an error.
     */
    private function clearLinterMessage(string $message): string
    {
        return Strings::replace($message, self::PHP_PARSE_ERROR_REGEX);
    }
}
