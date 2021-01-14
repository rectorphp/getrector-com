<?php

declare(strict_types=1);

namespace Rector\Website;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;

final class GetRectorKernel extends Kernel
{
    use MicroKernelTrait;

    protected function configureContainer(ContainerConfigurator $containerConfigurator): void
    {
        $containerConfigurator->import(__DIR__ . '/../config/services.php');

        $containerConfigurator->import(__DIR__ . '/../config/packages/*.php');
        $containerConfigurator->import(__DIR__ . '/../config/{packages}/' . $this->environment . '/*.php');
    }

    protected function configureRoutes(RoutingConfigurator $routingConfigurator): void
    {
        $routingConfigurator->import(__DIR__ . '/../config/routes.php');

        $routingConfigurator->import(__DIR__ . '/../config/{routes}/' . $this->environment . '/*.php', 'glob');
    }

    protected function build(ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->addCompilerPass(new AutowireArrayParameterCompilerPass());
    }
}
