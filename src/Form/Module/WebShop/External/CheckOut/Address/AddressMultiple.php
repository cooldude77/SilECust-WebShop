<?php

namespace App\Form\Module\WebShop\External\CheckOut\Address;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class   AddressMultiple extends AbstractType
{

    public const BILLING = 'billingAddresses';
    public const SHIPPING = 'shippingAddresses';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            self::BILLING, CollectionType::class, [
                'entry_type' => AddressSingle::class,
                'entry_options' => ['label' => false],]
        );
        $builder->add(
            self::SHIPPING, CollectionType::class, [
                'entry_type' => AddressSingle::class,
                'entry_options' => ['label' => false],]
        );
    }
}