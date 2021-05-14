<?php

declare(strict_types=1);

namespace Rector\Website\GitHubMagicLink\Twig;

use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\GitHubMagicLink\LinkFactory\FixtureLinkFactory;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class FixtureLinkTwigExtension extends AbstractExtension
{
    /**
     * @var string
     */
    private const PR_LINK = 'pr_link';

    public function __construct(
        private FixtureLinkFactory $fixtureLinkFactory
    ) {
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        $twigFilter = new TwigFilter(self::PR_LINK, fn (RectorRun $rectorRun): string => $this->fixtureLinkFactory->create(
            $rectorRun
        ));

        return [$twigFilter];
    }
}
