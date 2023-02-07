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
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/packages',
        __DIR__ . '/packages-tests',
    ]);

    $rectorConfig->importNames();

    $rectorConfig->parallel();

    $rectorConfig->sets([
        SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
        PHPUnitSetList::PHPUNIT_100,
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::NAMING,
        SetList::TYPE_DECLARATION,
    ]);

    $rectorConfig->import(LevelSetList::UP_TO_PHP_81);

    $rectorConfig->skip([
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
