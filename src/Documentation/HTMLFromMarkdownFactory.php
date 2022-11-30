<?php

declare(strict_types=1);

namespace Rector\Website\Documentation;

use Nette\Utils\FileSystem;
use ParsedownExtra;
use Webmozart\Assert\Assert;

final class HTMLFromMarkdownFactory
{
    public function __construct(
        private readonly ParsedownExtra $parsedownExtra
    ) {
    }

    public function create(string $markdownFilePath): string
    {
        Assert::fileExists($markdownFilePath);
        $sectionFileContents = FileSystem::read($markdownFilePath);

        return $this->parsedownExtra->parse($sectionFileContents);
    }
}
