<?php

declare(strict_types=1);

namespace Rector\Website\GitHubMagicLink\Twig;

use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\GitHubMagicLink\LinkFactory\IssueLinkFactory;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class IssueLinkTwigExtension extends AbstractExtension
{
    /**
     * @var string
     */
    private const ISSUE_LINK = 'issue_link';

    public function __construct(
        private IssueLinkFactory $issueLinkFactory
    ) {
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        $twigFilter = new TwigFilter(self::ISSUE_LINK, fn (RectorRun $rectorRun): string => $this->issueLinkFactory->create(
            $rectorRun
        ));

        return [$twigFilter];
    }
}
