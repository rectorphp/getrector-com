<?php

declare(strict_types=1);

use Rector\Website\Entity\RectorRun;
use Rector\Website\GitHubMagicLink\LinkFactory\FixtureLinkFactory;
use Rector\Website\GitHubMagicLink\LinkFactory\IssueLinkFactory;

function prLink(RectorRun $rectorRun): string
{
    $value = 100;

    /** @var FixtureLinkFactory $fixtureLinkFactory */
    $fixtureLinkFactory = app()
        ->make(FixtureLinkFactory::class);

    return $fixtureLinkFactory->create($rectorRun);
}

function issueLink(RectorRun $rectorRun): string
{
    /** @var IssueLinkFactory $issueLinkFactory */
    $issueLinkFactory = app()
        ->make(IssueLinkFactory::class);

    return $issueLinkFactory->create($rectorRun);
}
