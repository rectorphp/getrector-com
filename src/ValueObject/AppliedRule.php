<?php

declare(strict_types=1);

namespace App\ValueObject;

use App\Exception\ShouldNotHappenException;
use Nette\Utils\Strings;

final readonly class AppliedRule
{
    private string $shortRectorClass;

    public function __construct(
        private string $rectorClass
    ) {
        $shortRectorClassName = Strings::after($rectorClass, '\\', -1);
        if (! is_string($shortRectorClassName)) {
            throw new ShouldNotHappenException();
        }

        $this->shortRectorClass = $shortRectorClassName;
    }

    public function getShortClass(): string
    {
        return $this->shortRectorClass;
    }

    public function getTestFixtureNamespace(): string
    {
        $classParts = explode('\\', $this->rectorClass);

        array_splice($classParts, 1, 0, ['Tests']);
        $classParts[] = 'Fixture';

        return implode('\\', $classParts);
    }

    public function getTestFixtureDirectoryPath(): string
    {
        $classParts = explode('\\', $this->rectorClass);

        $category = $classParts[1];
        $rulesDirectory = 'rules-tests/' . $category;

        $nodeClass = $classParts[3];
        $shortClass = $classParts[4];

        return $rulesDirectory . '/Rector/' . $nodeClass . '/' . $shortClass . '/Fixture';
    }

    /**
     * Mimics @see \App\RuleFilter\ValueObject\RuleMetadata::getSlug()
     */
    public function getSlug(): string
    {
        // turn "SomeRector" to "some-rector"
        return str($this->shortRectorClass)
            ->snake('-')
            ->toString();
    }
}
