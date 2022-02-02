<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Demo\Helpers;

use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Website\Demo\Entity\RectorRun;
use Symfony\Component\Uid\Uuid;

final class DummyRectorRunFactory
{
    public function create(): RectorRun
    {
        $rectorRun = new RectorRun(
            Uuid::v4(),
            file_get_contents(__DIR__ . '/Source/rector_run_file_content.php.inc'),
            file_get_contents(__DIR__ . '/Source/rector_run_config_content.php.inc')
        );

        $jsonResult['file_diffs'][]['applied_rectors'] = [TypedPropertyRector::class];
        $rectorRun->setJsonResult($jsonResult);

        return $rectorRun;
    }
}
