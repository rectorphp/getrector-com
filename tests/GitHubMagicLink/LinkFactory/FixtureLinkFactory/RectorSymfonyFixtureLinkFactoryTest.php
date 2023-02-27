<?php

declare(strict_types=1);

namespace Rector\Website\Tests\GitHubMagicLink\LinkFactory\FixtureLinkFactory;

use Rector\Website\GitHubMagicLink\LinkFactory\FixtureLinkFactory;
use Rector\Website\Tests\AbstractTestCase;
use Rector\Website\Tests\Helpers\StringToArrayRunFactory;

final class RectorSymfonyFixtureLinkFactoryTest extends AbstractTestCase
{
    private FixtureLinkFactory $testFixtureLinkFactory;

    private StringToArrayRunFactory $stringToArrayRunFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testFixtureLinkFactory = $this->make(FixtureLinkFactory::class);
        $this->stringToArrayRunFactory = $this->make(StringToArrayRunFactory::class);
    }

    public function test(): void
    {
        $rectorRun = $this->stringToArrayRunFactory->create();
        $testFixtureLink = $this->testFixtureLinkFactory->create($rectorRun);

        $this->assertStringMatchesFormatFile(
            __DIR__ . '/FixtureRectorSymfony/expected_link.txt',
            $testFixtureLink . PHP_EOL
        );
    }
}
