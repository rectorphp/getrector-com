<?php

declare(strict_types=1);

use Sentry\Integration\IgnoreErrorsIntegration;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('sentry', [
        'dsn' => '%env(SENTRY_DSN)%',
        'options' => [
            'release' => '%env(SENTRY_RELEASE)%',
            'send_default_pii' => true,
            'attach_stacktrace' => true,
            'integrations' => [IgnoreErrorsIntegration::class],
        ],
    ]);

    // @see https://github.com/getsentry/sentry-symfony/blob/master/UPGRADE-4.0.md
    $services = $containerConfigurator->services();
    $services->set(IgnoreErrorsIntegration::class)
        ->arg('$options', [
            'ignore_exceptions' => [NotFoundHttpException::class],
        ]);
};
