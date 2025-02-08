<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getCacheDir(): string
    {
        return dirname(__DIR__) . '/var/' . $this->environment . '/cache';
    }

    public function getLogDir(): string
    {
        return dirname(__DIR__) . '/var/' . $this->environment . '/logs';
    }

    public function process(ContainerBuilder $container)
    {
        $twig = $container->getDefinition('twig.loader.native_filesystem');

        $twig->addMethodCall('addPath', [$container->getParameter('kernel.project_dir') . '/vendor/silecust/silecust/templates']);

    }


}
