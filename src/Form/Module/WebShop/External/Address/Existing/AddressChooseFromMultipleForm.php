<?php

namespace Silecust\WebShop\Form\Module\WebShop\External\Address\Existing;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressChooseFromMultipleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->add(
            'addresses',
            ChoiceType::class,
            [
                'choices' => $options['addressChoices'],
                'multiple' => false,
                'expanded' => true
            ]

        )
        ->add('Save',SubmitType::class,['label'=>'Choose']);

    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        //  $resolver->setDefault('data_class', AddressChooseExistingMultipleDTO::class);

        $resolver->setRequired(['addressChoices']);
    }

    public function getBlockPrefix()
    {
        return 'address_choose_existing_multiple_form';
    }


}