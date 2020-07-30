<?php

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Controller\TemplateController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routingConfigurator): void {
    $routingConfigurator->add('research_thank_you', 'research/thank-you')
        ->controller(TemplateController::class)
        ->defaults([
            'template' => 'research/thank-you.twig',
        ]);
};
