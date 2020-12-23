<?php

declare(strict_types=1);

namespace Rector\Website\Twig;

use Nette\Utils\Strings;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class RuleReadmeLinkTwigExtension extends AbstractExtension
{
    /**
     * @var string
     */
    private const README_URL = 'https://github.com/rectorphp/rector/blob/master/docs/rector_rules_overview.md';

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        $githubReadmeLinkTwigFilter = new TwigFilter('github_readme_link', function (string $shortRectorClass): string {
            return self::README_URL . '#' . Strings::webalize($shortRectorClass);
        });

        return [$githubReadmeLinkTwigFilter];
    }
}
