<?php

declare(strict_types=1);

namespace App\Tests\GitHubMagicLink\LinkFactory\FixtureLinkFactory;

use App\Entity\RectorRun;
use App\GitHubMagicLink\LinkFactory\FixtureLinkFactory;
use App\Tests\AbstractTestCase;
use App\Tests\Helpers\DowngradeArrayIsListFactory;
use App\Tests\Helpers\DummyRectorRunFactory;
use App\Tests\Helpers\StringToArrayRunFactory;
use Iterator;
use Override;
use PHPUnit\Framework\Attributes\DataProvider;

final class FixtureLinkFactoryTest extends AbstractTestCase
{
    private FixtureLinkFactory $testFixtureLinkFactory;

    #[Override]
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

    /**
     * @return Iterator<array<int, (RectorRun|string)>>
     */
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
