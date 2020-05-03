<?php

declare(strict_types=1);

namespace Rector\Website\Twig;

use Iterator;
use Nette\Utils\Strings;
use Rector\Website\Exception\ShouldNotHappenException;
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
    public function getFilters(): Iterator
    {
        yield new TwigFilter('github_readme_link', function (string $rectorClass): string {
            $shortClassName = Strings::after($rectorClass, '\\', -1);

            if (! is_string($shortClassName)) {
                throw new ShouldNotHappenException();
            }

            return self::README_URL . '#' . Strings::webalize($shortClassName);
        });
    }
}
