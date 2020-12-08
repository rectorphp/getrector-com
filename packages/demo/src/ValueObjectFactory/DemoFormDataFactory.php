<?php

declare(strict_types=1);

namespace Rector\Website\Demo\ValueObjectFactory;

use DateTimeImmutable;
use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\Demo\ValueObject\DemoFormData;
use Symplify\SmartFileSystem\SmartFileSystem;

/**
 * @see \Rector\Website\Demo\Tests\FormDataFactory\DemoFormDataFactoryTest
 */
final class DemoFormDataFactory
{
    /**
     * @var string
     */
    public const CONTENT_FILE_PATH = __DIR__ . '/../../data/DemoFile.php';

    /**
     * @var string
     */
    public const CONFIG_FILE_PATH = __DIR__ . '/../../data/demo-config.php';

    public function __construct(private SmartFileSystem $smartFileSystem)
    {
    }

    public function createFromRectorRun(?RectorRun $rectorRun): DemoFormData
    {
        if ($rectorRun !== null) {
            return new DemoFormData($rectorRun->getContent(), $rectorRun->getConfig());
        }

        // default values
        $demoContent = $this->smartFileSystem->readFile(self::CONTENT_FILE_PATH);
        $demoConfig = $this->smartFileSystem->readFile(self::CONFIG_FILE_PATH);

        return new DemoFormData($demoContent, $demoConfig);
    }

    public function createRectorRun(string $config, DemoFormData $demoFormData): RectorRun
    {
        return new RectorRun(new DateTimeImmutable(), $config, $demoFormData->getContent());
    }
}
