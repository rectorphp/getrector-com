<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Twig;

use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\Demo\IssueReporter\FixtureLinkFactory;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class TestFixtureLinkTwigExtension extends AbstractExtension
{
    public function __construct(private FixtureLinkFactory $testFixtureLinkFactory)
    {
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        $twigFilter = new TwigFilter('test_fixture_link', function (RectorRun $rectorRun): string {
            return $this->testFixtureLinkFactory->create($rectorRun);
        });

        return [$twigFilter];
    }
}
