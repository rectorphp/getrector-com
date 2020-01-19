<?php

declare(strict_types=1);

namespace Rector\Website\Lint;

use Rector\Website\Exception\LintingException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

final class YamlFileLinter
{
    public function checkFileSyntax(string $absolutePath): void
    {
        try {
            Yaml::parseFile($absolutePath);
        } catch (ParseException $parseException) {
            throw new LintingException($parseException->getMessage());
        }
    }
}
