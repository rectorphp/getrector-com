<?php

declare(strict_types=1);

namespace Rector\Website\Utils;

use Rector\Core\Application\VersionResolver;

/**
 * @api used in twig templates
 */
final class RectorVersionMetadata
{
    public function getReleaseVersion(): string
    {
        return str(VersionResolver::PACKAGE_VERSION)->substr(0, 6)->value();
    }

    public function getReleaseDate(): string
    {
        return substr(VersionResolver::RELEASE_DATE, 0, strlen(VersionResolver::RELEASE_DATE) - 3);
    }

    public function getCommitHash(): string
    {
        return str($this->getReleaseVersion())
            ->after('@')
            ->value();
    }
}
