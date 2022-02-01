<?php

declare(strict_types=1);

namespace Rector\Website\GitHubMagicLink\LinkFactory;

use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\GitHubMagicLink\BodyFactory\FixtureBodyFactory;
use Rector\Website\GitHubMagicLink\BodyFactory\PullRequestDescriptionFactory;

/**
 * @see \Rector\Website\GitHubMagicLink\Twig\FixtureLinkTwigExtension
 */
final class FixtureLinkFactory
{
    /**
     * @var string
     */
    private const BASE_URL = 'https://github.com/rectorphp/rector-src/new/main';

    public function __construct(
        private readonly FixtureBodyFactory $fixtureBodyFactory,
        private readonly PullRequestDescriptionFactory $pullRequestDescriptionFactory
    ) {
    }

    public function create(RectorRun $rectorRun): string
    {
        $content = $this->fixtureBodyFactory->create($rectorRun);

        $expectedRectorTestPath = $rectorRun->getExpectedRectorTestPath();

        $message = 'Add failing test fixture for ' . $rectorRun->getRectorShortClass();
        $description = $this->pullRequestDescriptionFactory->create($rectorRun);

        return self::BASE_URL . '/'
            . $expectedRectorTestPath
            . '?filename=Fixture/' . $rectorRun->getFixtureFileName()
            . '&value=' . urlencode($content)
            . '&message=' . urlencode($message)
            . '&description=' . urlencode($description);
    }
}
