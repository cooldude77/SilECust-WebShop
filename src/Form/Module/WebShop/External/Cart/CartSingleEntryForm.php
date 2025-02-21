<?php

namespace Silecust\WebShop\Form\Module\WebShop\External\Cart;

use Silecust\WebShop\Form\Module\WebShop\External\Cart\DTO\CartProductDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartSingleEntryForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder,
        array $options
    ): void {
        $builder->add(
            'productId',
            NumberType::class, ['label' => false]
        );
        $builder->add(
            'quantity',
            NumberType::class, ['label' => false]
        );

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => CartProductDTO::class]);


    }

    public function getBlockPrefix(): string
    {
        return 'cart_add_product_single_form';
    }

}