<?php

namespace App\Form\Module\WebShop\External\PaymentType;

use App\Repository\PaymentTypeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class PaymentTypeChoiceForm extends AbstractType
{
    public function __construct(private readonly PaymentTypeRepository $paymentTypeRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('paymentType', ChoiceType::class, [
                'choices' => $this->fill(),
                "multiple" => true,
                "expanded" => true,]
        );
        $builder->add('save', SubmitType::class);

    }

    private function fill(): array
    {

        $selectArray = [];
        $paymentTypes = $this->paymentTypeRepository->findAll();
        foreach ($paymentTypes as $paymentType) {

            $selectArray[$paymentType->getDescription()] = $paymentType->getId();
        }
        return $selectArray;

    }

    public function getBlockPrefix(): string
    {
        return 'web_shop_payment_type_choose_form';

    }

}