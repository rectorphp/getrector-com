<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('sentry', [
        'dsn' => '%env(SENTRY_DSN)%',
    ]);

    $containerConfigurator->extension('sentry', [
        'options' => [
            'release' => '%env(SENTRY_RELEASE)%',
            'send_default_pii' => true,
            'attach_stacktrace' => true,
            'excluded_exceptions' => [NotFoundHttpException::class],
        ],
    ]);
};
