<?php

declare(strict_types=1);

namespace Rector\Website\EntityFactory;

use Nette\Utils\FileSystem;
use Rector\Website\Entity\RectorRun;
use Symfony\Component\Uid\Uuid;

final class RectorRunFactory
{
    public function createEmpty(): RectorRun
    {
        // default values
        $filecontents = FileSystem::read(__DIR__ . '/../../resources/demo/DemoFile.php');
        $configContents = FileSystem::read(__DIR__ . '/../../resources/demo/demo-config.php');

        return new RectorRun(Uuid::v4(), $filecontents, $configContents);
    }
}
