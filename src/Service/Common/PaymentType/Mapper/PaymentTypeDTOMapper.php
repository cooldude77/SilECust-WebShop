<?php

namespace App\Service\Common\PaymentType\Mapper;

use App\Entity\Category;
use App\Entity\PaymentType;
use App\Repository\PaymentTypeRepository;
use Symfony\Component\Form\FormInterface;

class PaymentTypeDTOMapper
{
    private PaymentTypeRepository $paymentTypeRepository;

    public function __construct(PaymentTypeRepository $paymentTypeRepository)
    {

        $this->paymentTypeRepository = $paymentTypeRepository;
    }

    public function mapToEntityForCreate(FormInterface $form): PaymentType
    {
        $paymentTypeDTO = $form->getData();

        $paymentType = $this->paymentTypeRepository->create();

        $paymentType->setName($paymentTypeDTO->name);
        $paymentType->setDescription($paymentTypeDTO->description);


        return $paymentType;
    }


    public function mapToEntityForEdit(FormInterface $form, PaymentType $paymentType)
    {
        $paymentTypeDTO = $form->getData();

        $paymentType = $this->paymentTypeRepository->create();

        $paymentType->setName($paymentTypeDTO->name);
        $paymentType->setDescription($paymentTypeDTO->description);


        return $paymentType;

    }
}