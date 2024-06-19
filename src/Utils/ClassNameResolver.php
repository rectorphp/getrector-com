<?php

declare(strict_types=1);

namespace Rector\Website\Utils;

use Nette\Utils\Strings;
use Rector\Website\Exception\ShouldNotHappenException;

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
