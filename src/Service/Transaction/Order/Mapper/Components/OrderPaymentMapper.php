<?php

namespace App\Service\Transaction\Order\Mapper\Components;

use App\Entity\OrderHeader;
use App\Entity\OrderPayment;
use App\Repository\OrderPaymentRepository;
use App\Service\Module\WebShop\External\Payment\PaymentService;

readonly class OrderPaymentMapper
{
    public function __construct(private PaymentService $paymentService,
        private OrderPaymentRepository $orderPaymentRepository
    ) {
    }

    public function map(OrderHeader $orderHeader): OrderPayment
    {

        $orderPayment = $this->orderPaymentRepository->create($orderHeader);

        $orderPayment->setPaymentDetails([]);

        return $orderPayment;
    }
}