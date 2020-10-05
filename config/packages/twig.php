<?php

declare(strict_types=1);

use Rector\Website\Twig\RectorCountVariableProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('twig', [
        'default_path' => '%kernel.project_dir%/templates',
        'debug' => '%kernel.debug%',
        'strict_variables' => '%kernel.debug%',
        'exception_controller' => null,
        'globals' => [
            'site_url' => 'https://getrector.org',
            'main_page_title' => 'Rector - Automated Way to Instantly Upgrade and Refactor any PHP code',
            'rector_count_provider' => ref(RectorCountVariableProvider::class),
            'disqus_name' => 'getrectororg',
        ],
        'date' => [
            'format' => 'j. n., G:i',
        ],
        'number_format' => [
            'decimals' => 0,
            'decimal_point' => ',',
            'thousands_separator' => ' ',
        ],
    ]);
};
