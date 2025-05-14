<?php

declare(strict_types=1);

namespace App\ValueObject;

use App\Exception\ShouldNotHappenException;
use Nette\Utils\Strings;
use Webmozart\Assert\Assert;

final readonly class AppliedRule
{
    private const EXPECTED_CLASS_PARTS_COUNT = 5;
    private const CATEGORY_INDEX = 1;
    private const NODE_CLASS_INDEX = 3;
    private const SHORT_CLASS_INDEX = 4;

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
        
        // Validate class structure
        Assert::count(
            $classParts,
            self::EXPECTED_CLASS_PARTS_COUNT,
            sprintf(
                'Rector class "%s" must have exactly %d parts (e.g. "Rector\\Category\\Node\\SomeRector")',
                $this->rectorClass,
                self::EXPECTED_CLASS_PARTS_COUNT
            )
        );

        // Validate and get required parts
        $category = $this->getClassPart($classParts, self::CATEGORY_INDEX, 'category');
        $nodeClass = $this->getClassPart($classParts, self::NODE_CLASS_INDEX, 'node class');
        $shortClass = $this->getClassPart($classParts, self::SHORT_CLASS_INDEX, 'short class');

        $rulesDirectory = 'rules-tests/' . $category;

        return $rulesDirectory . '/Rector/' . $nodeClass . '/' . $shortClass . '/Fixture';
    }

    /**
     * @param string[] $classParts
     */
    private function getClassPart(array $classParts, int $index, string $partName): string
    {
        Assert::keyExists(
            $classParts,
            $index,
            sprintf('Missing %s in rector class "%s"', $partName, $this->rectorClass)
        );

        $part = $classParts[$index];
        Assert::notEmpty(
            $part,
            sprintf('Empty %s in rector class "%s"', $partName, $this->rectorClass)
        );

        return $part;
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
