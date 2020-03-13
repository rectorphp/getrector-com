<?php

declare(strict_types=1);

namespace Rector\Website\Demo\FormDataFactory;

use Nette\Utils\FileSystem;
use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\Demo\ValueObject\DemoFormData;

/**
 * @see \Rector\Website\Tests\FormDataFactory\DemoFormDataFactoryTest
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
    public const CONFIG_FILE_PATH = __DIR__ . '/../../data/demo-config.yaml';

    public function createFromRectorRun(?RectorRun $rectorRun): DemoFormData
    {
        if ($rectorRun) {
            return new DemoFormData($rectorRun->getContent(), $rectorRun->getConfig());
        }

        // default values
        $demoContent = FileSystem::read(self::CONTENT_FILE_PATH);
        $demoConfig = FileSystem::read(self::CONFIG_FILE_PATH);

        return new DemoFormData($demoContent, $demoConfig);
    }
}
