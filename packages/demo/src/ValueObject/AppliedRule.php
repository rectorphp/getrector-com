<?php

declare(strict_types=1);

namespace Rector\Website\Demo\ValueObject;

use Nette\Utils\Strings;
use Rector\Website\Exception\ShouldNotHappenException;
use Symplify\SymplifyKernel\Strings\StringsConverter;

final class AppliedRule
{
    /**
     * @var string
     */
    private const README_URL = 'https://github.com/rectorphp/rector/blob/master/docs/rector_rules_overview.md';

    private string $shortClass;

    public function __construct(
        private string $class
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

    public function getClass(): string
    {
        return $this->class;
    }

    public function getTestFixtureNamespace(): string
    {
        $classParts = explode('\\', $this->class);

        array_splice($classParts, 2, 0, ['Tests']);
        $classParts[] = 'Fixture';

        return implode('\\', $classParts);
    }

    public function getTestFixtureDirectoryPath(): string
    {
        $classParts = explode('\\', $this->class);

        $stringsConverter = new StringsConverter();
        $category = $stringsConverter->camelCaseToGlue($classParts[1], '-');
        $rulesDirectory = 'rules/' . $category;

        $nodeClass = $classParts[3];
        $shortClass = $classParts[4];

        return $rulesDirectory . '/tests/Rector/' . $nodeClass . '/' . $shortClass . '/Fixture';
    }

    public function getGitHubReadmeLink(): string
    {
        return self::README_URL . '#' . Strings::webalize($this->shortClass);
    }
}
