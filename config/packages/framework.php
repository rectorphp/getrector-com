<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symplify\Amnesia\Functions\env;
use Symplify\Amnesia\ValueObject\Symfony\Extension\FrameworkExtension;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension(FrameworkExtension::NAME, [
        FrameworkExtension::SECRET => env('APP_SECRET'),
        FrameworkExtension::CSRF_PROTECTION => [
            'enabled' => true,
        ],
        FrameworkExtension::HTTP_METHOD_OVERRIDE => true,
        FrameworkExtension::TRUSTED_HOSTS => null,
        FrameworkExtension::SESSION => [
            'handler_id' => null,
        ],
        FrameworkExtension::ESI => [
            'enabled' => true,
        ],
        FrameworkExtension::FRAGMENTS => [
            'enabled' => true,
        ],
        FrameworkExtension::PHP_ERRORS => [
            'log' => true,
        ],
        FrameworkExtension::IDE => 'phpstorm',
    ]);
};
