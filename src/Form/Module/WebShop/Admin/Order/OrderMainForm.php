<?php

namespace App\Form\Module\WebShop\Admin\Order;

use App\Form\Module\WebShop\Admin\Order\DTO\OrderItemDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderMainForm extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('header', OrderHeaderForm::class);
        $builder->add('items',CollectionType::class,['data_class'=>OrderItemForm::class]);
        $builder->add('Address',CollectionType::class,['data_class'=>OrderAddressForm::class]);

        $builder->add('save', SubmitType::class);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', OrderItemDTO::class);
    }

    public function getBlockPrefix(): string
    {
        return 'order_header_form';

    }
}