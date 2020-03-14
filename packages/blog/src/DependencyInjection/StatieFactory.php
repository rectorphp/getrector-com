<?php

declare(strict_types=1);

namespace Rector\Website\Blog\DependencyInjection;

use Psr\Container\ContainerInterface;
use Symplify\Statie\Configuration\StatieConfiguration;
use Symplify\Statie\Generator\Generator;
use Symplify\Statie\Generator\RelatedItemsResolver;
use Symplify\Statie\HttpKernel\StatieKernel;

/**
 * Inspired by https://raw.githubusercontent.com/pehapkari/pehapkari.cz/master/packages/Blog/src/DependencyInjection/StatieFactory.php
 */
final class StatieFactory
{
    private ContainerInterface $statieContainer;

    public function __construct()
    {
        $this->statieContainer = $this->createStatieKernel();
        $this->configure();
    }

    public function createGenerator(): Generator
    {
        return $this->statieContainer->get(Generator::class);
    }

    public function createRelatedItemsResolver(): RelatedItemsResolver
    {
        return $this->statieContainer->get(RelatedItemsResolver::class);
    }

    private function createStatieKernel(): ContainerInterface
    {
        $statieKernel = new StatieKernel('dev', true);
        $statieKernel->boot();

        return $statieKernel->getContainer();
    }

    private function configure(): void
    {
        /** @var StatieConfiguration $statieConfiguration */
        $statieConfiguration = $this->statieContainer->get(StatieConfiguration::class);
        $statieConfiguration->setDryRun(true);
        $statieConfiguration->setSourceDirectory(__DIR__ . '/../../../../statie');
    }
}
