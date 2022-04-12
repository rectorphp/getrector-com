<?php

declare(strict_types=1);

use Rector\CodingStyle\Enum\PreferenceSelfThis;
use Rector\CodingStyle\Rector\MethodCall\PreferThisOrSelfMethodCallRector;
use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([__DIR__ . '/src', __DIR__ . '/packages']);

    $rectorConfig->autoImportNames();

    $rectorConfig->parallel();

    $rectorConfig->sets([
        SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::NAMING,
        SetList::TYPE_DECLARATION,
    ]);

    $rectorConfig->import(LevelSetList::UP_TO_PHP_81);

    $rectorConfig->skip(['*/var/cache/*', __DIR__ . '/packages/Demo/data/DemoFile.php']);

    $services = $rectorConfig->services();
    $services->set(FinalizeClassesWithoutChildrenRector::class);

    $rectorConfig->ruleWithConfiguration(PreferThisOrSelfMethodCallRector::class, [
        'PHPUnit\Framework\TestCase' => PreferenceSelfThis::PREFER_THIS(),
    ]);
};
