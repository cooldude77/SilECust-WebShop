<?php

namespace App\Form\Module\WebShop\Admin\Order;

use App\Form\Module\WebShop\Admin\Order\DTO\OrderHeaderDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderHeaderForm extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('dateTimeOfOrder', DateType::class);
        $builder->add('idCustomer', NumberType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', OrderHeaderDTO::class);
    }

    public function getBlockPrefix(): string
    {
        return 'order_header_form';

    }
}