<?php

declare(strict_types=1);

namespace Rector\Website\Blog\Twig;

use ParsedownExtra;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class MarkdownTwigExtension extends AbstractExtension
{
    public function __construct(
        private readonly ParsedownExtra $parsedownExtra
    ) {
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('markdown', fn (?string $content): string => $this->parsedownExtra->parse($content)),
        ];
    }
}
