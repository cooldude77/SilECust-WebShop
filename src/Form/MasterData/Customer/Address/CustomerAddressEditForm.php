<?php

namespace App\Form\MasterData\Customer\Address;

use App\Form\MasterData\Customer\Address\Attribute\PinCode\PinCodeAutoCompleteField;
use App\Form\MasterData\Customer\Address\DTO\CustomerAddressDTO;
use App\Repository\PinCodeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerAddressEditForm extends AbstractType
{


    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('line1',TextType::class);
        $builder->add('line2', TextType::class);
        $builder->add('line3', TextType::class);
        $builder->add('pinCode', PinCodeAutoCompleteField::class,['mapped' => false]);
        $builder->add(
            'addressType', ChoiceType::class,
            [
                'choices' => [
                    'Shipping' => 'shipping',
                    'Billing' => 'billing',
                ],
                'multiple' => false,
                'expanded' => true,
            ]
        );
        $builder->add('isDefault', CheckboxType::class);

        $builder->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => CustomerAddressDTO::class]);
    }

    public function getBlockPrefix(): string
    {
        return 'customer_address_edit_form';
    }
}