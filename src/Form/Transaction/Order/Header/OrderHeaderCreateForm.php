<?php

namespace App\Form\Transaction\Order\Header;

use App\Form\MasterData\Customer\DTO\CustomerAutoCompleteField;
use App\Form\Transaction\Order\Header\DTO\OrderHeaderDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderHeaderCreateForm extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('customer', CustomerAutoCompleteField::class,['mapped'=>false]);
        $builder->add('choose', SubmitType::class);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) {

            $formEvent->getForm()->add('customerId', HiddenType::class);

            // dto jugglery
            $data = $formEvent->getData();

            $data['customerId'] = $data['customer'];

            $formEvent->setData($data);

        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => OrderHeaderDTO::class]);
    }

    public function getBlockPrefix(): string
    {

        return 'order_header_create_form';
    }

}