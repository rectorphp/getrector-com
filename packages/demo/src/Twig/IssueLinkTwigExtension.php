<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Twig;

use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\Demo\IssueReporter\IssueLinkFactory;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class IssueLinkTwigExtension extends AbstractExtension
{
    private IssueLinkFactory $gitHubIssueLinkFactory;

    public function __construct(IssueLinkFactory $gitHubIssueLinkFactory)
    {
        $this->gitHubIssueLinkFactory = $gitHubIssueLinkFactory;
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        $twigFilter = new TwigFilter('issue_link', function (RectorRun $rectorRun): string {
            return $this->gitHubIssueLinkFactory->create($rectorRun);
        });

        return [$twigFilter];
    }
}
