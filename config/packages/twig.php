<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('twig', [
        'form_themes' => ['bootstrap_4_layout.html.twig'],
        'default_path' => '%kernel.project_dir%/templates',
        'debug' => '%kernel.debug%',
        'strict_variables' => true,
        'globals' => [
            'site_url' => 'https://getrector.com',
            'main_page_title' => 'Rector - Automated Way to Instantly Upgrade and Refactor any PHP code',
            'disqus_name' => 'getrectororg',
        ],
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
