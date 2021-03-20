<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    $parameters->set(Option::PATHS, [__DIR__ . '/src', __DIR__ . '/packages']);

    $parameters->set(Option::SETS, [
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::NAMING,
        SetList::TYPE_DECLARATION,
        SetList::PHP_70,
        SetList::PHP_71,
        SetList::PHP_72,
        SetList::PHP_73,
        SetList::PHP_74,
        SetList::PHP_80,
    ]);

    $parameters->set(Option::SKIP, ['*/var/cache/*', __DIR__ . '/packages/demo/data/DemoFile.php']);
};
