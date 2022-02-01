<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\Amnesia\ValueObject\Symfony\Extension\Twig\NumberFormat;
use Symplify\Amnesia\ValueObject\Symfony\Extension\TwigExtension;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension(TwigExtension::NAME, [
        TwigExtension::FORM_THEMES => ['bootstrap_4_layout.html.twig'],
        TwigExtension::DEFAULT_PATH => '%kernel.project_dir%/templates',
        TwigExtension::DEBUG => '%kernel.debug%',
        TwigExtension::STRICT_VARIABLES => true,
        TwigExtension::EXCEPTION_CONTROLLER => null,
        TwigExtension::GLOBALS => [
            'site_url' => 'https://getrector.org',
            'main_page_title' => 'Rector - Automated Way to Instantly Upgrade and Refactor any PHP code',
            'disqus_name' => 'getrectororg',
        ],
        TwigExtension::DATE => [
            'format' => 'F d, Y',
        ],
        TwigExtension::NUMBER_FORMAT => [
            NumberFormat::DECIMALS => 0,
            NumberFormat::DECIMAL_POINT => ',',
            NumberFormat::THOUSANDS_SEPARATOR => ' ',
        ],
    ]);
};
