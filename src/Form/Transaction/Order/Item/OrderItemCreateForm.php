<?php

namespace Silecust\WebShop\Form\Transaction\Order\Item;

use Silecust\WebShop\Form\MasterData\Product\ProductAutoCompleteField;
use Silecust\WebShop\Form\Transaction\Order\Item\DTO\OrderItemDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderItemCreateForm extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('orderHeaderId', HiddenType::class);
        $builder->add('product', ProductAutoCompleteField::class,['mapped'=>false]);
        $builder->add('quantity', NumberType::class);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) {

            $formEvent->getForm()->add('productId', HiddenType::class);

            // dto jugglery
            $data = $formEvent->getData();

            $data['productId'] = $data['product'];

            $formEvent->setData($data);

        });
        $builder->add('save', SubmitType::class);

    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => OrderItemDTO::class]);
    }

    public function getBlockPrefix(): string
    {

        return 'order_item_create_form';
    }

}