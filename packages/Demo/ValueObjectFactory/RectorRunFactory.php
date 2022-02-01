<?php

declare(strict_types=1);

namespace Rector\Website\Demo\ValueObjectFactory;

use Rector\Website\Demo\Entity\RectorRun;
use Symplify\SmartFileSystem\SmartFileSystem;

final class RectorRunFactory
{
    /**
     * @var string
     */
    public const CONTENT_FILE_PATH = __DIR__ . '/../../../data/demo/DemoFile.php';

    /**
     * @var string
     */
    public const CONFIG_FILE_PATH = __DIR__ . '/../../../data/demo/demo-config.php';

    public function __construct(
        private readonly SmartFileSystem $smartFileSystem
    ) {
    }

    public function createEmpty(): RectorRun
    {
        // default values
        $content = $this->smartFileSystem->readFile(self::CONTENT_FILE_PATH);
        $config = $this->smartFileSystem->readFile(self::CONFIG_FILE_PATH);

        $rectorRun = new RectorRun();
        $rectorRun->setContent($content);
        $rectorRun->setConfig($config);

        return $rectorRun;
    }
}
