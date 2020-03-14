<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Tests\FormDataFactory;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\Demo\Form\FormDataFactory\DemoFormDataFactory;
use Rector\Website\GetRectorKernel;
use Symplify\PackageBuilder\Tests\AbstractKernelTestCase;

/**
 * @see \Rector\Website\Demo\Form\FormDataFactory\DemoFormDataFactory
 */
final class DemoFormDataFactoryTest extends AbstractKernelTestCase
{
    private DemoFormDataFactory $demoFormDataFactory;

    protected function setUp(): void
    {
        $this->bootKernel(GetRectorKernel::class);
        $this->demoFormDataFactory = self::$container->get(DemoFormDataFactory::class);
    }

    public function testWithNull(): void
    {
        $demoFormData = $this->demoFormDataFactory->createFromRectorRun(null);

        $this->assertStringEqualsFile(DemoFormDataFactory::CONTENT_FILE_PATH, $demoFormData->getContent());
        $this->assertStringEqualsFile(DemoFormDataFactory::CONFIG_FILE_PATH, $demoFormData->getConfig());
    }

    public function testWithRectoRun(): void
    {
        $rectorRun = new RectorRun(Uuid::uuid4(), new DateTimeImmutable(), 'some config', 'come content');
        $demoFormData = $this->demoFormDataFactory->createFromRectorRun($rectorRun);

        $this->assertSame($rectorRun->getContent(), $demoFormData->getContent());
        $this->assertSame($rectorRun->getConfig(), $demoFormData->getConfig());
    }
}
