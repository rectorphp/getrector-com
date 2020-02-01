<?php

declare(strict_types=1);

namespace Rector\Website\FormDataFactory;

use Nette\Utils\FileSystem;
use Rector\Website\Entity\RectorRun;
use Rector\Website\ValueObject\DemoFormData;

final class DemoFormDataFactory
{
    public function createFromRectorRun(?RectorRun $rectorRun): DemoFormData
    {
        if ($rectorRun) {
            return new DemoFormData($rectorRun->getContent(), $rectorRun->getConfig());
        }

        // default values
        $demoContent = FileSystem::read(__DIR__ . '/../../data/DemoFile.php');
        $demoConfig = FileSystem::read(__DIR__ . '/../../data/demo-config.yaml');

        return new DemoFormData($demoContent, $demoConfig);
    }
}
