<?php

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Controller\TemplateController;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('research_thank_you', ['path' => 'research/thank-you']);

    $containerConfigurator->extension('research_thank_you', ['controller' => TemplateController::class]);

    $containerConfigurator->extension('research_thank_you', [
        'defaults' => [
            'template' => 'research/thank-you.twig',
        ],
    ]);
};
