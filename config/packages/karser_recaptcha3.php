<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symplify\Amnesia\Functions\env;

// @see https://github.com/karser/KarserRecaptcha3Bundle#2-add-configuration-files
return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('karser_recaptcha3', [
        'site_key' => env('RECAPTCHA3_KEY'),
        'secret_key' => env('RECAPTCHA3_SECRET'),
        'score_threshold' => 0.5,
    ]);
};
