<?php

declare(strict_types=1);

namespace Rector\Website\Demo;

use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\Demo\Process\RectorProcessRunner;
use function Sentry\captureException;
use Throwable;

final class DemoRunner
{
    public function __construct(private RectorProcessRunner $rectorProcessRunner)
    {
    }

    public function processRectorRun(RectorRun $rectorRun): void
    {
        try {
            $jsonResult = $this->rectorProcessRunner->run($rectorRun->getContent(), $rectorRun->getConfig());
            $rectorRun->setJsonResult($jsonResult);
        } catch (Throwable $throwable) {
            $rectorRun->setFatalErrorMessage($throwable->getMessage());

            // @TODO change to monolog
            // Log to sentry
            captureException($throwable);
        }
    }
}
