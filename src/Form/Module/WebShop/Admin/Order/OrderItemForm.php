<?php

namespace App\Form\Module\WebShop\Admin\Order;

use App\Form\Module\WebShop\Admin\Order\DTO\OrderItemDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderItemForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('orderId', NumberType::class);
        $builder->add('productId', NumberType::class);
        $builder->add('quantity', NumberType::class);
        $builder->add('pricePerUnit', NumberType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', OrderItemDTO::class);
    }

    public function getBlockPrefix(): string
    {
        return 'order_item_form';

    }
}