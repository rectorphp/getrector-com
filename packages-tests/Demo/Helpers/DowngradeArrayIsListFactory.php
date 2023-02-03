<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Demo\Helpers;

use Rector\DowngradePhp81\Rector\FuncCall\DowngradeArrayIsListRector;
use Rector\Website\Demo\Entity\RectorRun;
use Symfony\Component\Uid\Uuid;

final class DowngradeArrayIsListFactory
{
    public function create(): RectorRun
    {
        $jsonResult = [];
        $rectorRun = new RectorRun(
            Uuid::v4(),
            file_get_contents(__DIR__ . '/Source/rector_run_file_content.php.inc'),
            file_get_contents(__DIR__ . '/Source/rector_run_config_content.php.inc')
        );

        $jsonResult['file_diffs'][]['applied_rectors'] = [DowngradeArrayIsListRector::class];
        $rectorRun->setJsonResult($jsonResult);

        return $rectorRun;
    }
}
