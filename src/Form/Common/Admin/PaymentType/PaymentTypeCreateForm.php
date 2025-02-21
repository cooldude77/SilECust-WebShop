<?php

namespace Silecust\WebShop\Form\Common\Admin\PaymentType;

use Silecust\WebShop\Form\CategoryAutoCompleteField;
use Silecust\WebShop\Form\Common\Admin\PaymentType\DTO\PaymentTypeDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentTypeCreateForm extends AbstractType
{

    // If one uses model transformer then only category id is provided in controller
    // instead, do not use it. You get a category entity object in mapper directly

   function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class);
        $builder->add('description', TextType::class);

        $builder->add('save', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => PaymentTypeDTO::class]);
    }


    public function getBlockPrefix(): string
    {
        return 'payment_type_create_form';
    }
}