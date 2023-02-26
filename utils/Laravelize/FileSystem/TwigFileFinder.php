<?php

declare(strict_types=1);

namespace Rector\Website\Utils\Laravelize\FileSystem;

use Webmozart\Assert\Assert;

final class TwigFileFinder
{
    /**
     * @return string[]
     */
    public function findTwigFilePaths(string $templatesDirectory): array
    {
        /** @var string[] $twigFilePaths */
        $twigFilePaths = glob($templatesDirectory . '/*/*.twig');
        Assert::allString($twigFilePaths);

        // use realpaths
        return array_map(function (string $twigFilePath): string {
            return realpath($twigFilePath);
        }, $twigFilePaths);
    }
}
