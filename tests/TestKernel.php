<?php

namespace App\Tests;

use App\SilecustWebShopBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class TestKernel extends BaseKernel implements CompilerPassInterface
{
    use MicroKernelTrait;
    public function registerBundles(): iterable
    {
        $bundles = array();

        if (in_array($this->getEnvironment(), array('test'))) {
            $bundles[] = new FrameworkBundle();
            $bundles[] = new DoctrineBundle();
           // $bundles[] = new SilecustWebShopBundle();
            $bundles[] = new TwigBundle();
        }

        return $bundles;
    }


    public function getCacheDir(): string
    {
          return dirname(__DIR__) . '/var/' . $this->environment . '/cache';
    }

    public function getLogDir(): string
    {
      return dirname(__DIR__).'/var/'.$this->environment.'/logs';
    }

    /**
     * @throws \Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
  //     $loader->load(__DIR__.'/config.yaml');

      /*  $confDir = $this->getProjectDir() . '/src/Resources/config';
        $loader->load($confDir .'/*' . '.yaml', 'glob');
*/
        $confDir = $this->getProjectDir() . '/src/Resources/config';
        $loader->load($confDir . '/{test}/*' . '.yaml', 'glob');
    }

    public function process(ContainerBuilder $container)
    {
        // TODO: Implement process() method.
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $confDir = $this->getProjectDir() . '/src/Resources/config';
        $loader->load($confDir . '/{test}/*' . '.yaml', 'glob');
    }


}
