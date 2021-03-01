<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symplify\Amnesia\Functions\env;
use Symplify\Amnesia\ValueObject\Symfony\Extension\FrameworkExtension;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension(FrameworkExtension::NAME, [
        'mailer' => [
            'dsn' => env('MAILER_DSN'),
        ],
    ]);
};
