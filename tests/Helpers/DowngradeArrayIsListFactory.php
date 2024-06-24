<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use App\Entity\RectorRun;
use Nette\Utils\FileSystem;
use Rector\DowngradePhp81\Rector\FuncCall\DowngradeArrayIsListRector;
use Symfony\Component\Uid\Uuid;

final class DowngradeArrayIsListFactory
{
    /**
     * @api used in tests
     */
    public function create(): RectorRun
    {
        $jsonResult = [];
        $rectorRun = new RectorRun(
            Uuid::v4(),
            FileSystem::read(__DIR__ . '/Source/rector_run_file_content.php.inc'),
            FileSystem::read(__DIR__ . '/Source/rector_run_config_content.php.inc')
        );

        $jsonResult['file_diffs'][]['applied_rectors'] = [DowngradeArrayIsListRector::class];
        $rectorRun->setJsonResult($jsonResult);

        return $rectorRun;
    }
}
