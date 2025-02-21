<?php

namespace Silecust\WebShop\Service\Module\WebShop\External\Product\Twig;

use Silecust\WebShop\Event\Module\WebShop\External\Product\Twig\SingleProductInProductListDisplayEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ProductInProductList extends AbstractExtension
{
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('dispatchWebShopProductListSingleProductEvent', [$this, 'raiseEvent']),
        ];
    }

    public function raiseEvent(string $eventName, mixed $data): mixed
    {
        $event = new SingleProductInProductListDisplayEvent();
        $event->setData($data);
        return $this->eventDispatcher->dispatch($event, $eventName);
    }
}