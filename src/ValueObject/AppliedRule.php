<?php

declare(strict_types=1);

namespace App\ValueObject;

use App\Exception\ShouldNotHappenException;
use Nette\Utils\Strings;

final class AppliedRule
{
    /**
     * @var string
     */
    private const README_URL = 'https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md';

    private readonly string $shortClass;

    public function __construct(
        private readonly string $class
    ) {
        $shortClassName = Strings::after($class, '\\', -1);
        if (! is_string($shortClassName)) {
            throw new ShouldNotHappenException();
        }

        $this->shortClass = $shortClassName;
    }

    public function getShortClass(): string
    {
        return $this->shortClass;
    }

    public function getTestFixtureNamespace(): string
    {
        $classParts = explode('\\', $this->class);

        array_splice($classParts, 1, 0, ['Tests']);
        $classParts[] = 'Fixture';

        return implode('\\', $classParts);
    }

    public function getTestFixtureDirectoryPath(): string
    {
        $classParts = explode('\\', $this->class);

        $category = $classParts[1];
        $rulesDirectory = 'rules-tests/' . $category;

        $nodeClass = $classParts[3];
        $shortClass = $classParts[4];

        return $rulesDirectory . '/Rector/' . $nodeClass . '/' . $shortClass . '/Fixture';
    }

    /**
     * @api used in blade
     */
    public function getGitHubReadmeLink(): string
    {
        return self::README_URL . '#' . Strings::webalize($this->shortClass);
    }
}
