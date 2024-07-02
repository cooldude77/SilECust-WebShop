<?php

namespace App\Form\Module\WebShop\External\Address\Existing;

use App\Form\Module\WebShop\External\Address\Existing\DTO\AddressChooseExistingMultipleDTO;
use App\Form\Module\WebShop\External\Address\Existing\DTO\AddressChooseExistingSingleDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressChooseFromMultipleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'addresses', CollectionType::class,
            ['entry_type' => AddressChooseExistingSingleForm::class]
        )
        ->add('Save',SubmitType::class,['label'=>'Choose']);

    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', AddressChooseExistingMultipleDTO::class);

    }

    public function getBlockPrefix()
    {
        return 'address_choose_existing_multiple_form';
    }


}