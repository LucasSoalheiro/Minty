<?php

namespace Src;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Nelmio\ApiDocBundle\NelmioApiDocBundle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class AppKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new NelmioApiDocBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
        ];
    }

    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }

    protected function configureContainer(ContainerConfigurator $container, LoaderInterface $loader, ContainerBuilder $builder): void
    {
        $container->import($this->getProjectDir() . '/src/Infra/Http/Config/services.yml');
        $container->import($this->getProjectDir() . '/src/Infra/Http/Config/packages/*.yaml');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import($this->getProjectDir() . '/src/Infra/Http/Config/routes/*.yaml');
        $routes->import($this->getProjectDir() . '/src/Infra/Http/Controller/', 'attribute');
    }
}
