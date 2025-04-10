<?php

namespace Silecust\WebShop\Tests;


use Silecust\WebShop\SilecustWebShopBundle;
use DAMA\DoctrineTestBundle\DAMADoctrineTestBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\UX\Autocomplete\AutocompleteBundle;
use SymfonyCasts\Bundle\ResetPassword\SymfonyCastsResetPasswordBundle;
use Zenstruck\Foundry\ZenstruckFoundryBundle;
use Zenstruck\Mailer\Test\ZenstruckMailerTestBundle;

class TestKernel extends Kernel
{
    use MicroKernelTrait;

    const string CONFIG_EXTS = '.{php,xml,yaml,yml}';

    public function registerBundles(): iterable
    {


        yield new FrameworkBundle();
        yield new SecurityBundle();

        yield new DoctrineBundle();
        yield new DoctrineMigrationsBundle();
        yield new MonologBundle();
        yield new ZenstruckFoundryBundle();
        yield new TwigBundle();
        yield new DAMADoctrineTestBundle();
        yield new KnpPaginatorBundle();
        yield new SymfonyCastsResetPasswordBundle();
        yield new AutocompleteBundle();
        yield new ZenstruckMailerTestBundle();
        yield new SilecustWebShopBundle();

    }

    private function getMainDirectory(): string
    {
        return __DIR__;
    }

    private function getConfigDirectory(): string
    {
        return $this->getMainDirectory() . '/config';
    }

    /**
     * @param ContainerBuilder $container
     * @param LoaderInterface $loader
     * @return void
     */
    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {

        /*  echo $this->getConfigDirectory();
          if (file_exists("/var/www/html/project/productized/bundles/silecust/tests/config"))
              echo "yes";
          die;
        */
        $loader->import($this->getConfigDirectory() . '/*' . self::CONFIG_EXTS, 'glob');


    }

    private function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import(__DIR__ . '/routes.yaml');


        // This also works
        //$routes->add('call', '/test')->controller(TestController ::class . "::home");
    }


}