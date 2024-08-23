<?php

namespace App\Form\MasterData\Price\Discount;

use App\Form\MasterData\Currency\CurrencyAutoCompleteField;
use App\Form\MasterData\Price\Discount\DTO\PriceProductDiscountDTO;
use App\Form\MasterData\Product\ProductAutoCompleteField;
use App\Repository\ProductRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceProductDiscountCreateForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('product', ProductAutoCompleteField::class, ['mapped' => false]);
        $builder->add('value', NumberType::class);
        $builder->add('currency', CurrencyAutoCompleteField::class, ['mapped' => false]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) {

            $formEvent->getForm()->add('productId', HiddenType::class);
             $formEvent->getForm()->add('currencyId', HiddenType::class);

            // dto jugglery
            $data = $formEvent->getData();

            $data['productId'] = $data['product'];
            $data['currencyId'] = $data['currency'];

            $formEvent->setData($data);

        });

        $builder->add('save', SubmitType::class);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => PriceProductDiscountDTO::class]);
    }

    public function getBlockPrefix(): string
    {

        return 'price_product_discount_create_form';
    }
}