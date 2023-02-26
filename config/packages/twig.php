<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('twig', [
        'default_path' => __DIR__ . '/../../resources/views',
        'debug' => '%kernel.debug%',
        'strict_variables' => true,
        'date' => [
            'format' => 'F d, Y',
        ],
        'number_format' => [
            'decimals' => 0,
            'decimal_point' => ',',
            'thousands_separator' => ' ',
        ],
    ]);
};
