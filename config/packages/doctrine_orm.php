<?php

declare(strict_types=1);

use Symfony\Config\Doctrine\OrmConfig;

return static function (OrmConfig $ormConfig): void {
    $ormConfig->autoGenerateProxyClasses(true);

    $defaultEntityManager = $ormConfig->entityManager('default')
        ->namingStrategy('doctrine.orm.naming_strategy.underscore')
        ->autoMapping(true);

    $defaultEntityManager->mapping('demo')
        ->isBundle(false)
        ->type('attribute')
        ->dir(__DIR__ . '/../../packages/Demo/Entity')
        ->prefix('Rector\Website\Demo\Entity');
};
