<?php

declare(strict_types=1);

namespace App\ValueObject;

use App\Exception\ShouldNotHappenException;
use Nette\Utils\Strings;
use Webmozart\Assert\Assert;

final readonly class AppliedRule
{
    private const EXPECTED_CLASS_PARTS_COUNT_MIN = 5;
    private const EXPECTED_CLASS_PARTS_COUNT_MAX = 6;
    private const CATEGORY_INDEX = 1;
    private const RECTOR_LITERAL_INDEX_5_PARTS = 2;
    private const RECTOR_LITERAL_INDEX_6_PARTS = 3;
    private const NODE_CLASS_INDEX_5_PARTS = 3;
    private const NODE_CLASS_INDEX_6_PARTS = 4;
    private const SHORT_CLASS_INDEX_5_PARTS = 4;
    private const SHORT_CLASS_INDEX_6_PARTS = 5;

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
        $partsCount = count($classParts);
        
        // Validate class structure - allow 5 or 6 parts (6 parts when version number is present)
        Assert::true(
            $partsCount >= self::EXPECTED_CLASS_PARTS_COUNT_MIN && $partsCount <= self::EXPECTED_CLASS_PARTS_COUNT_MAX,
            sprintf(
                'Rector class "%s" must have %d or %d parts (e.g. "Rector\\Category\\Rector\\Node\\SomeRector" or "Rector\\Category\\Version\\Rector\\Node\\SomeRector")',
                $this->rectorClass,
                self::EXPECTED_CLASS_PARTS_COUNT_MIN,
                self::EXPECTED_CLASS_PARTS_COUNT_MAX
            )
        );

        // Validate first part is "Rector"
        Assert::same($classParts[0], 'Rector', sprintf('Rector class "%s" must start with "Rector"', $this->rectorClass));

        // Determine if we have a version part (6 parts) or not (5 parts)
        $hasVersion = $partsCount === self::EXPECTED_CLASS_PARTS_COUNT_MAX;
        
        // Validate "Rector" literal is at the correct position
        $rectorLiteralIndex = $hasVersion ? self::RECTOR_LITERAL_INDEX_6_PARTS : self::RECTOR_LITERAL_INDEX_5_PARTS;
        Assert::same(
            $classParts[$rectorLiteralIndex],
            'Rector',
            sprintf('Rector class "%s" must have "Rector" at index %d', $this->rectorClass, $rectorLiteralIndex)
        );

        // Get required parts with correct indices
        // When there's a version (6 parts), use the version as the category; otherwise use the base category
        $categoryIndex = $hasVersion ? 2 : self::CATEGORY_INDEX;
        $category = $this->getClassPart($classParts, $categoryIndex, 'category');
        $nodeClassIndex = $hasVersion ? self::NODE_CLASS_INDEX_6_PARTS : self::NODE_CLASS_INDEX_5_PARTS;
        $shortClassIndex = $hasVersion ? self::SHORT_CLASS_INDEX_6_PARTS : self::SHORT_CLASS_INDEX_5_PARTS;
        
        $nodeClass = $this->getClassPart($classParts, $nodeClassIndex, 'node class');
        $shortClass = $this->getClassPart($classParts, $shortClassIndex, 'short class');

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
