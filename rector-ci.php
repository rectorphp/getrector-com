<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PATHS, [__DIR__ . '/src', __DIR__ . '/packages']);

    $parameters->set(
        Option::SETS,
        [SetList::DEAD_CODE, SetList::CODE_QUALITY, SetList::CODING_STYLE, SetList::TYPE_DECLARATION]
    );

    $parameters->set(
        Option::EXCLUDE_PATHS,
        ['*/var/cache/*', '*/adminer/index.php', __DIR__ . '/packages/demo/data/DemoFile.php']
    );
};
