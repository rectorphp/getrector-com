<?php

declare(strict_types=1);

namespace Rector\Website\EntityFactory;

use Rector\Website\Entity\RectorRun;
use Symfony\Component\Uid\Uuid;
use Symplify\SmartFileSystem\SmartFileSystem;

final class RectorRunFactory
{
    /**
     * @var string
     */
    private const CONTENT_FILE_PATH = __DIR__ . '/../../../data/demo/DemoFile.php';

    /**
     * @var string
     */
    private const CONFIG_FILE_PATH = __DIR__ . '/../../../data/demo/demo-config.php';

    public function __construct(
        private readonly SmartFileSystem $smartFileSystem
    ) {
    }

    public function createEmpty(): RectorRun
    {
        // default values
        $content = $this->smartFileSystem->readFile(self::CONTENT_FILE_PATH);
        $config = $this->smartFileSystem->readFile(self::CONFIG_FILE_PATH);

        return new RectorRun(Uuid::v4(), $content, $config);
    }
}
