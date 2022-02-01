<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Validator;

use Nette\Utils\Strings;
use Rector\Website\Demo\Exception\Linter\MissingPHPOpeningTagException;
use Rector\Website\Demo\Exception\LintingException;
use Rector\Website\Demo\Lint\PHPLinter;
use Rector\Website\Exception\ShouldNotHappenException;

/**
 * @see \Rector\Website\Tests\Demo\Validator\PHPConstraintValidatorTest
 */
final class PHPConstraintValidator
{
    /**
     * @see https://regex101.com/r/Y1Qmks/1
     * @var string
     */
    private const PHP_PARSE_ERROR_REGEX = '#PHP Parse error\:\s+syntax error\,\s+#';

    public function __construct(
        private readonly PHPLinter $phpLinter
    ) {
    }

    public function validate(string $value): void
    {
        try {
            $this->phpLinter->checkContentSyntax($value);
        } catch (MissingPHPOpeningTagException) {
            throw new ShouldNotHappenException('Add opening "<?php" tag');
        } catch (LintingException $lintingException) {
            $errorMessage = 'Fix PHP syntax: ' . $lintingException->getMessage();
            $usefulLinterMessage = $this->clearLinterMessage($errorMessage);
            throw new ShouldNotHappenException($usefulLinterMessage);
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
