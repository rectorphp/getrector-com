<?php

declare(strict_types=1);

namespace Rector\Website\Demo\IssueReporter;

use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\Exception\ShouldNotHappenException;

/**
 * @see \Rector\Website\Demo\Tests\IssueReporter\TestFixtureLinkFactoryTest
 */
final class FixtureLinkFactory
{
    /**
     * @var string
     */
    private const BASE_URL = 'https://github.com/rectorphp/rector/new/master';

    public function __construct(private FixtureBodyFactory $fixtureBodyFactory)
    {
    }

    public function create(RectorRun $rectorRun): string
    {
        $content = $this->fixtureBodyFactory->create($rectorRun);

        $expectedRectorTestPath = $rectorRun->getExpectedRectorTestPath();
        if ($expectedRectorTestPath === null) {
            throw new ShouldNotHappenException('Test can be create only if exactly 1 rule is responsible');
        }

        return self::BASE_URL . '/' . $expectedRectorTestPath . '?filename=' . $rectorRun->getFixtureFileName() . '&value=' . urlencode(
            $content
        );
    }
}
