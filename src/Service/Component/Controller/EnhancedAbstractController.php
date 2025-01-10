<?php

namespace App\Service\Component\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;

class EnhancedAbstractController extends AbstractController
{
    const string TWIG_PRE_RENDER_EVENT = "TWIG_PRE_RENDER_EVENT";

    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {

    }

    public function render($view, array $parameters = [], ?Response $response = null): Response
    {

        $event = new TwigPreRenderEvent($view, $parameters, $response);

        $this->eventDispatcher->dispatch($event, self::TWIG_PRE_RENDER_EVENT);

        return parent::render($event->getView(), $event->getParameters(), $event->getResponse());
    }
}