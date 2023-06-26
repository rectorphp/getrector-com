<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Helpers;

use Nette\Utils\FileSystem;
use Rector\Website\Entity\RectorRun;
use Symfony\Component\Uid\Uuid;
use Rector\Symfony\Symfony42\Rector\New_\StringToArrayArgumentProcessRector;

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
