<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use App\Entity\RectorRun;
use Nette\Utils\FileSystem;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;
use Symfony\Component\Uid\Uuid;

final class DummyRectorRunFactory
{
    /**
     * @api used in tests
     */
    public static function create(): RectorRun
    {
        $jsonResult = [];

        $rectorRun = new RectorRun(
            Uuid::v4(),
            FileSystem::read(__DIR__ . '/Source/rector_run_file_content.php.inc'),
            FileSystem::read(__DIR__ . '/Source/rector_run_config_content.php.inc')
        );

        $jsonResult['file_diffs'][]['applied_rectors'] = [TypedPropertyFromAssignsRector::class];
        $rectorRun->setJsonResult($jsonResult);

        return $rectorRun;
    }
}
