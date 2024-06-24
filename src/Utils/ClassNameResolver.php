<?php

declare(strict_types=1);

namespace App\Utils;

use App\Exception\ShouldNotHappenException;
use Nette\Utils\Strings;

final class ClassNameResolver
{
    /**
     * @var string
     * @see https://regex101.com/r/tttAOn/1
     */
    private const NAMESPACE_REGEX = '#\bnamespace\s+(?<namespace>[\w\\\\]+);#';

    /**
     * @var string
     * @see https://regex101.com/r/B7LvXE/1
     */
    private const SHORT_CLASS_NAME_REGEX = '#\bclass\s+(?<short_class_name>[A-Z][A-Za-z]+)#';

    /**
     * @see https://regex101.com/r/13A0W9/1
     * @var string
     */
    private const CLASS_NAME_REGEX = '#class\s+(?<' . self::PART_CLASS_NAME . '>\w+)#';

    /**
     * @var string
     */
    private const PART_CLASS_NAME = 'class_name';

    public static function resolveShortClassName(string $fileContents): ?string
    {
        $matches = Strings::match($fileContents, self::CLASS_NAME_REGEX);
        return $matches[self::PART_CLASS_NAME] ?? null;
    }

    public static function resolveFromFileContents(string $fileContents, string $filePath): string
    {
        // @todo use php-parser to make more reliable?

        $namespaceMatch = Strings::match($fileContents, self::NAMESPACE_REGEX);
        $classMatch = Strings::match($fileContents, self::SHORT_CLASS_NAME_REGEX);

        // short class must exist
        if (! isset($classMatch['short_class_name'])) {
            throw new ShouldNotHappenException(sprintf('Unable to resolve class from "%s" file', $filePath));
        }

        return ($namespaceMatch['namespace'] ?? '') . '\\' . $classMatch['short_class_name'];
    }
}
