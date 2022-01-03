<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Rector\CodingStyle\Enum\PreferenceSelfThis;
use Rector\CodingStyle\Rector\MethodCall\PreferThisOrSelfMethodCallRector;
use Rector\Core\Configuration\Option;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(FinalizeClassesWithoutChildrenRector::class);

    $services->set(PreferThisOrSelfMethodCallRector::class)
        ->configure([
            TestCase::class => PreferenceSelfThis::PREFER_THIS(),
        ]);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    $parameters->set(Option::PATHS, [__DIR__ . '/src', __DIR__ . '/packages']);

    $containerConfigurator->import(SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES);
    $containerConfigurator->import(DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES);
    $containerConfigurator->import(PHPUnitSetList::PHPUNIT_CODE_QUALITY);
    $containerConfigurator->import(SetList::DEAD_CODE);
    $containerConfigurator->import(SetList::CODE_QUALITY);
    $containerConfigurator->import(SetList::CODING_STYLE);
    $containerConfigurator->import(SetList::NAMING);
    $containerConfigurator->import(SetList::TYPE_DECLARATION);

    $containerConfigurator->import(LevelSetList::UP_TO_PHP_80);

    $parameters->set(Option::SKIP, ['*/var/cache/*', __DIR__ . '/packages/Demo/data/DemoFile.php']);
};
