<?php

declare(strict_types=1);

namespace Rector\Website;

use Iterator;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;
use Symplify\Autodiscovery\Discovery;
use Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;
use Symplify\FlexLoader\Flex\FlexLoader;

final class GetRectorKernel extends Kernel
{
    use MicroKernelTrait;

    private FlexLoader $flexLoader;

    private Discovery $discovery;

    public function __construct(string $environment, bool $debug)
    {
        parent::__construct($environment, $debug);

        $this->flexLoader = new FlexLoader($environment, $this->getProjectDir());
        $this->discovery = new Discovery($this->getProjectDir());
    }

    /**
     * @return Iterator<BundleInterface>
     */
    public function registerBundles(): Iterator
    {
        return $this->flexLoader->loadBundles();
    }

    protected function configureContainer(ContainerBuilder $containerBuilder, LoaderInterface $loader): void
    {
        $this->discovery->discoverEntityMappings($containerBuilder);
        $this->discovery->discoverTemplates($containerBuilder);
        $this->discovery->discoverTranslations($containerBuilder);

        $this->flexLoader->loadConfigs($containerBuilder, $loader, [
            // project's packages
            $this->getProjectDir() . '/packages/*/config/*',
        ]);
    }

    protected function configureRoutes(RouteCollectionBuilder $routeCollectionBuilder): void
    {
        $this->discovery->discoverRoutes($routeCollectionBuilder);

        $this->flexLoader->loadRoutes($routeCollectionBuilder, [
            // project's packages
            //            $this->getProjectDir() . '/packages/*/src/Controller/*',
        ]);
    }

    protected function build(ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->addCompilerPass(new AutowireArrayParameterCompilerPass());
    }
}
