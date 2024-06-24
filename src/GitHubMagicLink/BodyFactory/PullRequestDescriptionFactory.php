<?php

declare(strict_types=1);

namespace App\GitHubMagicLink\BodyFactory;

use App\Entity\RectorRun;

/**
 * @see \App\Tests\GitHubMagicLink\BodyFactory\PullRequestDescriptionFactory\PullRequestDescriptionFactoryTest
 */
final class PullRequestDescriptionFactory
{
    public function create(RectorRun $rectorRun): string
    {
        $bodyLines = [];

        $bodyLines[] = '# Failing Test for ' . $rectorRun->getRectorShortClass();
        $bodyLines[] = 'Based on https://getrector.com/demo/' . $rectorRun->getUuid();

        $body = implode(PHP_EOL . PHP_EOL, $bodyLines);
        return $body . PHP_EOL;
    }
}
