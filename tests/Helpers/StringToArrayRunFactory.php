<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use App\Entity\RectorRun;
use Nette\Utils\FileSystem;
use Rector\Symfony\Symfony42\Rector\New_\StringToArrayArgumentProcessRector;
use Symfony\Component\Uid\Uuid;

/**
 * @api used in tests
 */
final class StringToArrayRunFactory
{
    public function create(): RectorRun
    {
        $jsonResult = [];
        $rectorRun = new RectorRun(
            Uuid::v4(),
            FileSystem::read(__DIR__ . '/Source/rector_run_file_content.php.inc'),
            FileSystem::read(__DIR__ . '/Source/rector_run_config_content.php.inc')
        );

        $jsonResult['file_diffs'][]['applied_rectors'] = [StringToArrayArgumentProcessRector::class];
        $rectorRun->setJsonResult($jsonResult);

        return $rectorRun;
    }
}
