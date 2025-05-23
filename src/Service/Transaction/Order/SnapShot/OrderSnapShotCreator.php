<?php

namespace Silecust\WebShop\Service\Transaction\Order\SnapShot;

use Symfony\Component\Serializer\SerializerInterface;

/**
 * Order object for mapping from cart and other details into OrderObjectDTO
 */
class OrderSnapShotCreator
{
    public function __construct(SerializerInterface $serializer)
    {
    }

// should be called only after order object is ready
    public function createSnapShot(\Silecust\WebShop\Entity\OrderHeader $orderHeader): SnapshotObject
    {

        $snap = new SnapshotObject();

        return $snap;

    }

}