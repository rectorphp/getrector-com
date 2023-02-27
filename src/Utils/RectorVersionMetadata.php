<?php

declare(strict_types=1);

namespace Rector\Website\Utils;

use PackageVersions\Versions;
use Rector\Core\Application\VersionResolver;

/**
 * @api used in twig templates
 */
final class RectorVersionMetadata
{
    public function getReleaseVersion(): string
    {
        $rectorVersion = Versions::getVersion('rector/rector');
        $extractAt = explode('@', $rectorVersion);

        return $extractAt[0] . '@' . substr($extractAt[1], 0, 6);
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
