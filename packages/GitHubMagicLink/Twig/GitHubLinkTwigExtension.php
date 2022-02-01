<?php

declare(strict_types=1);

namespace Rector\Website\GitHubMagicLink\Twig;

use Rector\Website\GitHubMagicLink\LinkFactory\FixtureLinkFactory;
use Rector\Website\GitHubMagicLink\LinkFactory\IssueLinkFactory;
use Rector\WebsiteDemoRunner\Entity\RectorRun;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class GitHubLinkTwigExtension extends AbstractExtension
{
    public function __construct(
        private readonly FixtureLinkFactory $fixtureLinkFactory,
        private readonly IssueLinkFactory $issueLinkFactory,
    ) {
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        $prLinkTwigFilter = new TwigFilter('pr_link', fn (RectorRun $rectorRun): string => $this->fixtureLinkFactory->create(
            $rectorRun
        ));

        $issueLinkTwigFilter = new TwigFilter('issue_link', fn (RectorRun $rectorRun): string => $this->issueLinkFactory->create(
            $rectorRun
        ));

        return [$prLinkTwigFilter, $issueLinkTwigFilter];
    }
}
