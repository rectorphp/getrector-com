<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Demo\Helpers;

use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;
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

        $jsonResult['file_diffs'][]['applied_rectors'] = [TypedPropertyFromAssignsRector::class];
        $rectorRun->setJsonResult($jsonResult);

        return $rectorRun;
    }
}
