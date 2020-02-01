<?php

declare(strict_types=1);

namespace Rector\Website\Tests\FormDataFactory;

use DateTimeImmutable;
use Iterator;
use Ramsey\Uuid\Uuid;
use Rector\Website\Entity\RectorRun;
use Rector\Website\Error\ErrorMessageNormalizer;
use Rector\Website\FormDataFactory\DemoFormDataFactory;
use Rector\Website\GetRectorKernel;
use Symplify\PackageBuilder\Tests\AbstractKernelTestCase;

/**
 * @see \Rector\Website\FormDataFactory\DemoFormDataFactory
 */
final class DemoFormDataFactoryTest extends AbstractKernelTestCase
{
    /**
     * @var DemoFormDataFactory
     */
    private $demoFormDataFactory;

    protected function setUp(): void
    {
        self::bootKernel(GetRectorKernel::class);
        $this->demoFormDataFactory = self::$container->get(DemoFormDataFactory::class);
    }

    public function testWithNull(): void
    {
        $demoFormData = $this->demoFormDataFactory->createFromRectorRun(null);

        $this->assertStringContainsString('we never get here', $demoFormData->getContent());
        $this->assertStringContainsString('dead-code', $demoFormData->getConfig());
    }

    public function testWithRectoRun(): void
    {
        $rectorRun = new RectorRun(Uuid::uuid4(), new DateTimeImmutable(), 'some config', 'come content');
        $demoFormData = $this->demoFormDataFactory->createFromRectorRun($rectorRun);

        $this->assertSame($rectorRun->getContent(), $demoFormData->getContent());
        $this->assertSame($rectorRun->getConfig(), $demoFormData->getConfig());
    }
}
