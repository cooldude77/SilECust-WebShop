<?php

namespace Silecust\WebShop\Service\Transaction\Order\Mapper\Components;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Entity\OrderPayment;
use Silecust\WebShop\Repository\OrderPaymentRepository;
use Silecust\WebShop\Service\Module\WebShop\External\Payment\PaymentService;

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