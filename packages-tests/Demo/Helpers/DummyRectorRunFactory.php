<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Tests\Helpers;

use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Website\Demo\Entity\RectorRun;
use Symfony\Component\Uid\Uuid;
use Symplify\SmartFileSystem\SmartFileSystem;

final class DummyRectorRunFactory
{
    private SmartFileSystem $smartFileSystem;

    public function __construct()
    {
        $this->smartFileSystem = new SmartFileSystem();
    }

    public function create(): RectorRun
    {
        $rectorRun = new RectorRun();
        $rectorRun->setId(Uuid::v4());
        $rectorRun->setContent($this->smartFileSystem->readFile(__DIR__ . '/Source/rector_run_file_content.php.inc'));
        $rectorRun->setConfig($this->smartFileSystem->readFile(__DIR__ . '/Source/rector_run_config_content.php.inc'));

        $jsonResult['file_diffs'][]['applied_rectors'] = [TypedPropertyRector::class];
        $rectorRun->setJsonResult($jsonResult);

        return $rectorRun;
    }
}
