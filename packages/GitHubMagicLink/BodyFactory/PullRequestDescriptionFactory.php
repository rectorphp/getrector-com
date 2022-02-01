<?php

declare(strict_types=1);

namespace Rector\Website\GitHubMagicLink\BodyFactory;

use Rector\WebsiteDemoRunner\Entity\RectorRun;

/**
 * @see \Rector\Website\Tests\GitHubMagicLink\BodyFactory\PullRequestDescriptionFactory\PullRequestDescriptionFactoryTest
 */
final class PullRequestDescriptionFactory
{
    public function create(RectorRun $rectorRun): string
    {
        $bodyLines = [];

        $bodyLines[] = '# Failing Test for ' . $rectorRun->getRectorShortClass();
        $bodyLines[] = 'Based on https://getrector.org/demo/' . $rectorRun->getId();

        $body = implode(PHP_EOL . PHP_EOL, $bodyLines);
        return $body . PHP_EOL;
    }
}
