<?php

declare(strict_types=1);

namespace App\Utils;

use Rector\Application\VersionResolver;

/**
 * @api used in templates
 */
final class RectorMetadata
{
    public static function getReleaseVersion(): string
    {
        return str(VersionResolver::PACKAGE_VERSION)->substr(0, 6)->value();
    }

    public static function getReleaseDate(): string
    {
        return substr(VersionResolver::RELEASE_DATE, 0, strlen(VersionResolver::RELEASE_DATE) - 3);
    }
}
