<?php

namespace App\Form\Module\WebShop\External\Order\DTO\Components;

class OrderPaymentDTO
{
    private ?int $id = 0;

    private ?int $orderHeaderId = 0;

    private array $paymentDetails = [];

    private ?bool $status = null;


}