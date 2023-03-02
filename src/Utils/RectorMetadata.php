<?php

declare(strict_types=1);

namespace Rector\Website\Utils;

use Rector\Core\Application\VersionResolver;

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
