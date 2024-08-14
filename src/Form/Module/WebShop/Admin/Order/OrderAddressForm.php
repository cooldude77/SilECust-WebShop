<?php

namespace App\Form\Module\WebShop\Admin\Order;

use App\Form\Module\WebShop\Admin\Order\DTO\OrderAddressDTO;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderAddressForm extends AbstractType
{
    public int $id = 0;
    public int $orderId = 0;

    public ?string $line1 = null;
    public ?string $line2 = null;
    public ?string $line3 = null;
    public ?string $pinCode = null;

    public ?string $city = null;

    public ?string $state = null;

    public ?string $country= null;

    public bool $isShipping=false;
    public bool $isBilling = false;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('orderId', NumberType::class);
        $builder->add('line1', TextType::class);
        $builder->add('line2', TextType::class);
        $builder->add('pinCode', TextType::class);
        $builder->add('city', TextType::class);
        $builder->add('state', TextType::class);
        $builder->add('country', TextType::class);
        $builder->add('isShipping', CheckboxType::class);
        $builder->add('isBilling', CheckboxType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', OrderAddressDTO::class);
    }

    public function getBlockPrefix(): string
    {
        return 'order_address_form';

    }
}