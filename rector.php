<?php

declare(strict_types=1);

use Rector\CodingStyle\Enum\PreferenceSelfThis;
use Rector\CodingStyle\Rector\MethodCall\PreferThisOrSelfMethodCallRector;
use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([__DIR__ . '/config', __DIR__ . '/src', __DIR__ . '/tests']);

    $rectorConfig->importNames();

    $rectorConfig->sets([
        PHPUnitSetList::PHPUNIT_100,
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::NAMING,
        SetList::TYPE_DECLARATION,
        LevelSetList::UP_TO_PHP_81,
    ]);

    $rectorConfig->skip([
        // generated mess
        __DIR__ . '/config/bootstrap.php',
        '*/var/cache/*',
        // on purpose
        \Rector\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector::class,
        \Rector\TypeDeclaration\Rector\ClassMethod\ArrayShapeFromConstantArrayReturnRector::class,
    ]);

    $rectorConfig->rule(FinalizeClassesWithoutChildrenRector::class);
    $rectorConfig->ruleWithConfiguration(PreferThisOrSelfMethodCallRector::class, [
        'PHPUnit\Framework\TestCase' => PreferenceSelfThis::PREFER_THIS,
    ]);
};
