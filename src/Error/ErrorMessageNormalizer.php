<?php

declare(strict_types=1);

namespace Rector\Website\Error;

use Nette\Utils\Strings;

final class ErrorMessageNormalizer
{
    /**
     * @see https://regex101.com/r/8Q2WnL/2/
     * @var string
     */
    private const CLASS_NOT_FOUND = '#(?<type>(Class|Interface|Trait)) \'(?<class_like_name>.*?)\' not found in \/project\/rector_analyzed_file\.php#';

    public function normalize(string $errorMessage): string
    {
        $match = Strings::match($errorMessage, self::CLASS_NOT_FOUND);
        if (! $match) {
            return $errorMessage;
        }

        $type = lcfirst($match['type']);
        $classLikeName = $match['class_like_name'];

        return sprintf(
            '%s "%s" is missing. Complete it to code input, e.g. "%s %s {}"',
            ucfirst($type),
            $classLikeName,
            $type,
            $classLikeName
        );
    }
}
