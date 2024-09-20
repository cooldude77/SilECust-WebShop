<?php

namespace Silecust\ThemeManagementBundle\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

class ResponseSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly RouterInterface $router,
        /*
              private readonly ParameterBagInterface $parameterBag
          */
    )
    {
    }

    public function onKernelView(ViewEvent $event): void
    {
          $route = $this->router->match($event->getRequest()->getPathInfo());

          $parameterName = "param_route.{$route['_route']}.twig_template_location";

          if ($this->parameterBag->get($parameterName == null)) {

          }

          $x = 0;

    }

    public function onController(ControllerEvent $event): void
    {
        $x = 0;
    }

    public static function getSubscribedEvents(): array
    {
        return [

            KernelEvents::VIEW => 'onKernelView',
            KernelEvents::CONTROLLER => 'onController',
        ];
    }
}
