<?php

declare(strict_types=1);

namespace Rector\Website\Blog\Twig;

use ParsedownExtra;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class MarkdownTwigExtension extends AbstractExtension
{
    private ParsedownExtra $parsedownExtra;

    public function __construct(ParsedownExtra $parsedownExtra)
    {
        $this->parsedownExtra = $parsedownExtra;
    }

    /**
     * @return \Twig\TwigFilter[]
     */
    public function getFilters(): iterable
    {
        yield new TwigFilter('markdown', function (?string $content): string {
            return $this->parsedownExtra->parse($content);
        });
    }
}
