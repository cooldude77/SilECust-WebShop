<?php

namespace Silecust\WebShop\Form\Module\WebShop\External\Cart;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class CartMultipleEntryForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder,
        array $options
    ): void {
        $builder->add(
            'items',
            CollectionType::class,
            ['entry_type' => CartSingleEntryForm::class]
        );

        $builder->add('update', SubmitType::class, ['label' => 'Update Cart']);
        $builder->add('checkout', SubmitType::class, ['label' => 'Checkout']);


    }

}