<?php

declare(strict_types=1);

namespace Rector\Website\Tests\GitHubMagicLink\LinkFactory\FixtureLinkFactory;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Website\Entity\RectorRun;
use Rector\Website\GitHubMagicLink\LinkFactory\FixtureLinkFactory;
use Rector\Website\Tests\AbstractTestCase;
use Rector\Website\Tests\Helpers\DowngradeArrayIsListFactory;
use Rector\Website\Tests\Helpers\DummyRectorRunFactory;
use Rector\Website\Tests\Helpers\StringToArrayRunFactory;

final class FixtureLinkFactoryTest extends AbstractTestCase
{
    private FixtureLinkFactory $testFixtureLinkFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testFixtureLinkFactory = $this->make(FixtureLinkFactory::class);
    }

    #[DataProvider('provideData')]
    public function test(RectorRun $rectorRun, string $expectedLink): void
    {
        $testFixtureLink = $this->testFixtureLinkFactory->create($rectorRun);

        $this->assertStringMatchesFormatFile($expectedLink, $testFixtureLink . PHP_EOL);
    }

    public static function provideData(): Iterator
    {
        $rectorRun = DummyRectorRunFactory::create();
        yield [$rectorRun, __DIR__ . '/Fixture/expected_link.txt'];

        $downgradeArrayIsListFactory = new DowngradeArrayIsListFactory();

        $rectorRun = $downgradeArrayIsListFactory->create();
        yield [$rectorRun, __DIR__ . '/Fixture/downgrade-expected_link.txt'];

        $stringToArrayRunFactory = new StringToArrayRunFactory();

        $rectorRun = $stringToArrayRunFactory->create();
        yield [$rectorRun, __DIR__ . '/Fixture/symfony_expected_link.txt'];
    }
}
