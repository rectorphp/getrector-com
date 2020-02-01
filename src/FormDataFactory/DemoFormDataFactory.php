<?php

declare(strict_types=1);

namespace Rector\Website\FormDataFactory;

use Nette\Utils\FileSystem;
use Rector\Website\Entity\RectorRun;
use Rector\Website\ValueObject\DemoFormData;

final class DemoFormDataFactory
{
    /**
     * @var string
     */
    private const CONTENT_FILE_PATH = __DIR__ . '/../../data/DemoFile.php';

    /**
     * @var string
     */
    private const CONFIG_FILE_PATH = __DIR__ . '/../../data/demo-config.yaml';

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
