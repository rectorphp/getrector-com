<?php

declare(strict_types=1);

use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Config\Doctrine\DbalConfig;
use function Symplify\Amnesia\Functions\env;

return static function (DbalConfig $dbalConfig): void {
    $dbalConfig->connection('default')
        ->driver('pdo_mysql')
        ->serverVersion('5.7')
        ->host(env('DATABASE_HOST'))
        ->dbname(env('DATABASE_PORT'))
        ->user(env('DATABASE_USER'))
        ->password(env('DATABASE_PASSWORD'))
        ->charset(env('DATABASE_CHARSET'))
        ->mappingType('uuid', UuidType::class);
};
