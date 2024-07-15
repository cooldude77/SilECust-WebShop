<?php

namespace App\Form\Common\Order\Header;

use App\Form\MasterData\Customer\DTO\CustomerAutoCompleteField;
use App\Form\Transaction\Admin\Order\Header\OrderHeaderDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderHeaderCreateForm extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('customer', CustomerAutoCompleteField::class);
        $builder->add('choose', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => OrderHeaderDTO::class]);
    }
}