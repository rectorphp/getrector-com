<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Tests\IssueReporter;

use Rector\Website\Demo\IssueReporter\FixtureLinkFactory;
use Rector\Website\Demo\Tests\Helpers\DummyRectorRunFactory;
use Rector\Website\GetRectorKernel;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class FixtureLinkFactoryTest extends AbstractKernelTestCase
{
    private FixtureLinkFactory $testFixtureLinkFactory;

    private DummyRectorRunFactory $dummyRectorRunFactory;

    protected function setUp(): void
    {
        $this->bootKernel(GetRectorKernel::class);
        $this->testFixtureLinkFactory = $this->getService(FixtureLinkFactory::class);
        $this->dummyRectorRunFactory = new DummyRectorRunFactory();
    }

    public function test(): void
    {
        $rectorRun = $this->dummyRectorRunFactory->create();
        $testFixtureLink = $this->testFixtureLinkFactory->create($rectorRun);

        $this->assertSame(
            'https://github.com/rectorphp/rector/new/master/rules/php74/tests/Rector/Property/TypedPropertyRector/Fixture?filename=any_class.php.inc&value=%3C%3Fphp%0A%0Anamespace+Rector%5CPhp74%5CTests%5CRector%5CProperty%5CTypedPropertyRector%5CFixture%3B%0A%0Aclass+AnyClass%0A%7B%0A++++public+function+run%28%29%0A++++%7B%0A++++++++echo+%27some+PHP%27%3B%0A++++%7D%0A%7D%0A%0A%3F%3E%0A-----%0A%3C%3Fphp%0A%0Anamespace+Rector%5CPhp74%5CTests%5CRector%5CProperty%5CTypedPropertyRector%5CFixture%3B%0A%0A%2F%2F+what+is+expected+code%3F%0A%2F%2F+should+remain+the+same%3F+delete+part+bellow+-----+%28included%29%0A%0A%3F%3E%0A',
            $testFixtureLink
        );
    }
}
